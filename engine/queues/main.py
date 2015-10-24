#!/usr/bin/env python
# -*- coding: utf-8 -*-

# Copyright 2015 Marcelo Koti Kamada, Maria Lydia Fioravanti
#
# This file is part of I3S (Interactive Systems Scheduling Simulator)
#
# I3S is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# I3S is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with I3S.  If not, see <http://www.gnu.org/licenses/>.

# python engine/round_robin/main.py -d engine/round_robin/en.xml -j '[{"nome":"A","tempo":"10","tipo":"cpu","valor":""},{"nome":"B","tempo":"15","tipo":"cpu","valor":""},{"nome":"C","tempo":"17","tipo":"io","valor":""},{"nome":"D","tempo":"13","tipo":"cpu","valor":""},{"nome":"E","tempo":"10","tipo":"io","valor":""},{"nome":"F","tempo":"25","tipo":"cpu","valor":""}]' -q "5" -s "1" -i "5" -p "1"

import os
import re
import json
import optparse
from xml.dom import minidom

# ########## dicionario, suporte a linguas ##########
dicionario = {}

# ########## round robin ##########
# comeca o tempo em 0
current_time = 0
time_stamp = 0
numero_switches = 0
tempo_cpu = 0

# arranjar um jeito de pegar o quantum
quantum = -1
switch_cost = -1
io_operation_time = -1
processing_time_until_io = -1

# lista de processos
processos = []
lista_bloqueados = []

ocorreu_evento = False
viewport = 0
viewport_process = None

# lista de processos
nqueues = 4
queues = []

class Processo:
    def __init__(self, nome, tempo, tipo, quantum):
        self.nome = nome                    # nome do processo
        self.remaining_time = int(tempo)    # tempo ate terminar
        self.tipo = tipo                    # tipo, io ou cpu
        self.io_remaining_time = 0          # tempo para terminar o I/O 
        self.remaining_quantum = quantum    # tempo de quantum remanescente
        self.fila = 0
        self.quantum_stacks = 0
        self.quantum_helper = 0

    def to_string(self):
        return "[" + self.nome + ":" + str(self.tipo) + ":" + str(self.remaining_time) + ":" + str(self.io_remaining_time) + ":" + str(self.fila) + "]"

def atualiza_lista_bloqueados():
    global processos
    global ocorreu_evento
    global lista_bloqueados
    global queues

    j = 0
    # para cada processo, eu preciso atualizar o tempo de I/O deles
    for i in range(len(lista_bloqueados)):
        processo = lista_bloqueados[j]
        processo.io_remaining_time = processo.io_remaining_time - 1

        if(processo.io_remaining_time <= 0):
            processo.io_remaining_time = 0
            lista_bloqueados.remove(processo)
            queues[processo.fila].append(processo) # mudei aqui

            # o quantum que o processo tem e' sempre o menor
            if(processing_time_until_io <= 2 ** processo.fila):
                processo.remaining_quantum = processing_time_until_io
            else:
                processo.remaining_quantum = 2 ** processo.fila

            if(processo.quantum_stacks != 0):   # se ele nao terminou o quantum da fila
                if(2 ** processo.fila - processo.quantum_stacks < processo.remaining_quantum):
                    processo.remaining_quantum = 2 ** processo.fila - processo.quantum_stacks

            print('time_stamp=' + str(time_stamp) + '&id=msg&value=' + dicionario['process_goes_to_ready_list'] % (processo.nome))
            ocorreu_evento = True
            j = j - 1
        j = j + 1

def output():
    global tempo_cpu
    global current_time
    global time_stamp
    global numero_switches
    global processos
    global lista_bloqueados
    global queues
    global viewport
    global viewport_process

    # imprime a lista de processos
    msg = 'time_stamp=' + str(time_stamp) + '&id=status&value='
    for fila_aux in queues:
        for processo in fila_aux:
            msg = msg + processo.to_string() + " "
    msg = msg + ","
    for processo in lista_bloqueados:
        msg = msg + processo.to_string() + " "
    print(msg)

    # imprime o tempo total de execucao
    print('time_stamp=' + str(time_stamp) + '&id=tte&value=' + str(current_time))

    # imprime numero de switches
    print('time_stamp=' + str(time_stamp) + '&id=switches&value=' + str(numero_switches))

    # imprime o uso da CPU
    tmp = 0.0
    if current_time > 0:
        tmp = float(tempo_cpu*100) / current_time
    print('time_stamp=' + str(time_stamp) + '&id=cpu&value=' + str(int(tmp)) + '%')

    if(viewport_process != None):
        print('time_stamp=' + str(time_stamp) + '&id=viewport&value=' + str(viewport) + ',' + viewport_process.nome)
    else:
        print('time_stamp=' + str(time_stamp) + '&id=viewport&value=' + str(viewport) + ',-')

    time_stamp = time_stamp + 1

def main():
    global processos
    global lista_bloqueados
    global quantum
    global switch_cost
    global io_operation_time
    global processing_time_until_io

    global tempo_cpu
    global current_time
    global time_stamp
    global numero_switches
    global ocorreu_evento
    global queues
    global nqueues
    global viewport
    global viewport_process

    parser = optparse.OptionParser('usage%prog -d <dictionary> -j <json data> -q <quantum> -s <switch_cost> -i <io_time> -p <processing_time>')
    parser.add_option('-j', dest='jname', type='string', help='json data')
    parser.add_option('-d', dest='dname', type='string', help='specify dictionary file')

    parser.add_option('-q', dest='qname', type='int', help='quantum')
    parser.add_option('-s', dest='sname', type='int', help='process switch cost')
    parser.add_option('-i', dest='iname', type='int', help='time of one I/O operation')
    parser.add_option('-p', dest='pname', type='int', help='processing time until I/O')

    (options, args) = parser.parse_args()

    if (options.jname == None) | (options.dname == None) | (options.qname == None) | (options.sname == None) | (options.iname == None) | (options.pname == None):
        print(parser.usage)
        exit(0)

    quantum = options.qname
    switch_cost = options.sname
    io_operation_time = options.iname
    processing_time_until_io = options.pname

    # ########## carrega o dicionario de mensagens ##########
    try:
        arquivo = open(options.dname, 'r')
        arquivo.close()
    except IOError as e:
        print("I/O error({0}): {1}".format(e.errno, e.strerror))
        exit(0)

    doc = minidom.parse(options.dname)

    messages = doc.getElementsByTagName('message')
    for message in messages:
        name = message.getAttribute('name')
        value = message.getElementsByTagName('value')[0].firstChild.data
        dicionario[name] = value

    # ########## inicializa as filas multiplas ##########
    for i in range(nqueues):
        queues.append([])      # queues tem o formato [ [], [], []. [] ]

    # ########## itera pelo json, adicionando processos na lista ##########
    json_string = options.jname[1:-1]
    json_list = []

    regex = re.compile('{(?P<a>\w+):(?P<b>\w+),(?P<c>\w+):(?P<d>\w+),(?P<e>\w+):(?P<f>\w+),(?P<g>\w+):(?P<h>\w+)?}')
    result = re.findall('{.*?}', json_string)
    for item in result:
        aux2 = item
        if(os.name == 'nt'):
            aux = regex.search(item)
            aux2 = '{"' + aux.group('a') + '":"' + aux.group('b') + '","' + aux.group('c') + '":"' + aux.group('d') + '","' + aux.group('e') + '":"' + aux.group('f') + '","' + aux.group('g') + '":"'
            if(aux.group('h') != None):
                aux2 = aux2  + aux.group('h') + '"}'
            else:
                aux2 = aux2 + '"}'
        json_list.append(aux2)

    for s in json_list:
        parsed_json = json.loads(s)
        p = Processo(parsed_json['nome'], parsed_json['tempo'], parsed_json['tipo'], 1) # todos os processos com 1 de quantum
        queues[0].append(p)  # append todos os processos na primeira lista

    flag_to_be_blocked = False
    to_be_blocked = None
    switch_timer = 0
    viewport = 0
    output()

    ultima_lista = None
    escolhido_terminou = True

    # algoritimo do round robin, enquanto ainda ha' processos na lista
    while True:
        viewport = 0

        nprocessos = 0
        processos = []
        for fila in queues:
            nprocessos = nprocessos + len(fila)        # calcula o numero de processos restantes

            if(escolhido_terminou == False):
                processos = ultima_lista

            # escolha de qual lista de processos atender
            if(len(fila) != 0 and len(processos) == 0):
                processos = fila
                escolhido_terminou = False
                ultima_lista = processos

        # se nao tem mais nenhum processo para ser escalonado e ninguem esta bloqueado termina
        if(nprocessos == 0 and len(lista_bloqueados) == 0):
            break

        if(switch_timer <= 0):          # quando nao estiver em uma troca de contexto
            if(len(processos) > 0):     # se tem processos prontos
                ready = processos[0]
                viewport_process = ready
                ready.remaining_time = ready.remaining_time - 1
                ready.remaining_quantum = ready.remaining_quantum - 1
                ready.quantum_stacks = ready.quantum_stacks + 1
                ready.quantum_helper = ready.quantum_helper + 1
                tempo_cpu = tempo_cpu + 1
                viewport = 3

                if(ready.remaining_time <= 0):      # se o processo terminou
                    processos.remove(ready)         # remove o processo da lista
                    switch_timer = switch_cost + 1  # tem troca de contexto
                    numero_switches = numero_switches + 1
                    viewport = 2

                    tmp = 2 ** ready.fila
                    if(ready.tipo == "io" and processing_time_until_io < 2 ** ready.fila):
                        tmp = processing_time_until_io

                    print('time_stamp=' + str(time_stamp) + '&id=msg&value=' + dicionario['process_finishes'] % (ready.nome, str(tmp - ready.remaining_quantum)))
                    ocorreu_evento = True
                    escolhido_terminou = True
                    ultimo_escolhido = None

                elif(ready.remaining_quantum <= 0):     # se o quantum dele acabou
                    switch_timer = switch_cost + 1      # tem troca de contexto
                    numero_switches = numero_switches + 1
                    escolhido_terminou = True
                    ultimo_escolhido = None

                    if(ready.tipo == "cpu"):            # se for cpu bound
                        print('time_stamp=' + str(time_stamp) + '&id=msg&value=' + dicionario['process_goes_to_end_of_ready_list'] % (ready.nome, str(2 ** ready.fila - ready.remaining_quantum)))

                        if(ready.fila + 1 < nqueues):
                            ready.fila = ready.fila + 1

                        processos.remove(ready)         # remove ele da lista
                        queues[ready.fila].append(ready) # vai para o fim da lista 
                        ocorreu_evento = True
                        ready.remaining_quantum = 2 ** ready.fila   # reseta o quantum

                    else: # se for io bound
                        flag_to_be_blocked = True
                        to_be_blocked = ready
                        ocorreu_evento = True

                        # arrumar aqui para mostrar o tempo certo
                        tmp = ready.quantum_helper
                        #tmp = processing_time_until_io
                        #if(processing_time_until_io > 2 ** ready.fila):
                        #    tmp = 2 ** ready.fila
                        print('time_stamp=' + str(time_stamp) + '&id=msg&value=' + dicionario['process_executes_partially'] % (ready.nome, str(tmp - ready.remaining_quantum)))

                    ready.quantum_helper = 0

        # para cada processo, eu preciso atualizar o tempo de I/O deles
        atualiza_lista_bloqueados()

        if(switch_timer > 0):               # se esta em um troca de contexto
            switch_timer = switch_timer - 1 # decrementa o timer

            if(switch_timer == 0):          # se terminou a troca de contexto
                ocorreu_evento = True
                viewport = 1
                print('time_stamp=' + str(time_stamp) + '&id=msg&value=' + dicionario['context_switch'] % (switch_cost))

                if(flag_to_be_blocked):     # tem algum processo esperando para ser bloqueado
                    ready = to_be_blocked
                    queues[ready.fila].remove(ready) # remove ele da lista

                    if(ready.quantum_stacks >= 2 ** ready.fila and ready.fila + 1 < nqueues):
                        ready.fila = ready.fila + 1
                        ready.quantum_stacks = 0

                    lista_bloqueados.append(ready)              # coloca ele na lista de bloqueados
                    ready.io_remaining_time = io_operation_time # o tempo de I/O dele e' o tempo de um I/O

                    flag_to_be_blocked = False          # nao tem ninguem para ser bloqueado
                    print('time_stamp=' + str(time_stamp) + '&id=msg&value=' + dicionario['process_goes_to_blocked_list'] % (ready.nome, str(ready.io_remaining_time)))

        current_time = current_time + 1
        if(ocorreu_evento):
            output()

        ocorreu_evento = False

    # lembra de tirar o switch_cost a mais que eu to contando
    current_time = current_time - switch_cost
    print('time_stamp=' + str(time_stamp) + '&id=tte&value=' + str(current_time))
    print('time_stamp=' + str(time_stamp) + '&id=cpu&value=' + str(int(float(tempo_cpu*100) / current_time)) + '%')

if __name__ == '__main__':
    main()


