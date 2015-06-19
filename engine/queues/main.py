#!/usr/bin/env python
# -*- coding: utf-8 -*-

import re
import json
import optparse
from xml.dom import minidom

# ########## dicionario, suporte a linguas ##########
dicionario = {}

# ########## round robin ##########
# comeca o tempo em 0
current_time = 0

# arranjar um jeito de pegar o quantum
quantum = -1
switch_cost = -1
io_operation_time = -1
processing_time_until_io = -1

# lista de processos
nqueues = 4
queues = []

processos = []
lista_bloqueados = []

class Processo:
    def __init__(self, nome, tempo, tipo, remaining_time_until_io):
        self.nome = nome
        self.tempo = int(tempo)
        self.tipo = tipo
        self.io_time = 0
        self.remaining_time_until_io = int(remaining_time_until_io)

    def to_string(self):
        return "[" + self.nome + ":" + str(self.tipo) + ":" + str(self.tempo) + ":" + str(self.io_time) + ":" + str(0) + "]"

def atualiza_lista_bloqueados(tempo_utilizado):
    # para cada processo, eu preciso atualizar o tempo de I/O deles
    for processo in lista_bloqueados:
        processo.io_time = processo.io_time - (tempo_utilizado + switch_cost)

        if(processo.io_time < 0):
            processo.io_time = 0
            lista_bloqueados.remove(processo)
            queues[0][1].append(processo)
            print('id=msg&value=' + dicionario['process_goes_to_ready_list'] % (processo.nome))

def main():
    global processos
    global lista_bloqueados
    global current_time
    global quantum
    global switch_cost
    global io_operation_time
    global processing_time_until_io

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
    for i in range(0, nqueues):
        queues.append([i, []])

    # ########## itera pelo json, adicionando processos na lista ##########
    json_string = options.jname[1:-1]
    json_list = []

    result = re.findall('{.*?}', json_string)
    for item in result:
        json_list.append(item)

    for s in json_list:
        parsed_json = json.loads(s)
        tmp = -1
        if(parsed_json['tipo'] == 'io'):
            tmp = processing_time_until_io

        p = Processo(parsed_json['nome'], parsed_json['tempo'], parsed_json['tipo'], tmp)
        queues[0][1].append(p)  # append todos os processos na primeira lista

    numero_switches = 0
    tempo_cpu = 0

    # algoritimo do round robin, enquanto ainda ha' processos na lista
    while True:

        quantum_da_fila = -1
        nprocessos = 0
        processos = []
        for fila in queues:
            nprocessos = nprocessos + len(fila[1])

            # escolha de qual lista de processos atender
            if(len(fila[1]) != 0 and len(processos) == 0):
                processos = fila[1]
                quantum_da_fila = fila[0]

        # se nao tem mais nenhum processo para ser escalonado e ninguem esta bloqueado
        if(nprocessos == 0 and len(lista_bloqueados) == 0):
            break

        # imprime a lista de processos
        msg = 'id=status&value='
        for processo in processos:
            msg = msg + processo.to_string() + " "
        msg = msg + ","
        for processo in lista_bloqueados:
            msg = msg + processo.to_string() + " "
        print(msg)

        # imprime o tempo total de execucao
        print('id=tte&value=' + str(current_time))

        # imprime numero de switches
        print('id=switches&value=' + str(numero_switches))

        # imprime o uso da CPU
        tmp = 0.0
        if current_time > 0:
            tmp = float(tempo_cpu*100) / current_time
        print('id=cpu&value=' + str(int(tmp)) + '%')

        if(len(processos) == 0 and len(lista_bloqueados) > 0):
            p = lista_bloqueados[0]
            current_time = current_time + p.io_time
            atualiza_lista_bloqueados(p.io_time)
            continue

        # pega o primeiro da lista
        p = processos[0]

        # o processo esta pronto
        tempo_utilizado = 0
        aux_quantum = 0
        aux_io = 0

        # se o processo for do tipo IO bound
        if(p.tipo == "io"):
            # se o quantum da fila e' menor do que falta para o processo sofre I/O
            # executa o quantum da fila, e decrementa o quanto que falta para o I/O do processo
            if(2^quantum_da_fila < p.remaining_time_until_io):
                aux_quantum = 2^quantum_da_fila
                p.remaining_time_until_io = p.remaining_time_until_io - 2^quantum_da_fila
                aux_io = 0 # sem I/O porque ainda nao deu o tempo
                # para a proxima classe
            # senao, o processo vai receber I/O antes de acabar o quantum da fila
            # executa o ate dar I/O, reseta o tempo para I/O para o valor global
            else:
                aux_quantum = p.remaining_time_until_io     # o quantum dele e' o tempo ate ele fazer I/O
                p.remaining_time_until_io = processing_time_until_io
                aux_io = io_operation_time                  # o tempo de I/O dele e' o tempo de um I/O
                # volta para classe 1

        # se o processo for do tipo CPU bound
        else:
            if(quantum <= 2^quantum_da_fila):
                aux_quantum = quantum   # ele vai executar o quantum inteiro
            else:
                aux_quantum = 2^quantum_da_fila
            aux_io = 0              # o tempo de I/O dele e' nenhum

        # se falta menos do que o ele vai executar
        if(p.tempo < aux_quantum):
            tempo_utilizado = p.tempo   # ele so' usa o que precisa
            p.tempo = 0                 # o tempo que ele precisa acaba

        # senao, ele vai precisar mais do que vai executar
        else:
            tempo_utilizado = aux_quantum   # executa o quantum dele
            p.tempo = p.tempo - aux_quantum # e decrementa quanto de tempo ele precisa
            p.io_time = aux_io              # acerta o tempo de I/O, tem que somar a mais pq eu vou tirar em baixo

        # soma o tempo que o processo usou mais o tempo de chavear
        current_time = current_time + tempo_utilizado + switch_cost

        # para cada processo, eu preciso atualizar o tempo de I/O deles
        atualiza_lista_bloqueados(tempo_utilizado)

        # se o processo acabou, remove ele da lista
        if(p.tempo == 0):
            processos.remove(p)
            print('id=msg&value=' + dicionario['process_finishes'] % (p.nome, str(tempo_utilizado)))
        # senao coloca ele no fim da lista
        else:
            if(p.tipo == "cpu"):    # vai para o fim da lista
                processos.remove(p)

                if(quantum_da_fila+1 < nqueues):
                    queues[quantum_da_fila+1][1].append(p)
                else:
                    queues[quantum_da_fila][1].append(p)

                #print(dicionario['process_goes_to_end_of_ready_list'])
                print('id=msg&value=' + dicionario['process_goes_to_end_of_ready_list'] % (p.nome, str(tempo_utilizado)))

            # se for I/O bound
            else:                   # vai para a lista de bloqueados
                # se nao tem I/O
                # passa o processo para a proxima classe
                if(aux_io == 0):
                    processos.remove(p)
                    if(quantum_da_fila+1 < nqueues):
                        queues[quantum_da_fila+1][1].append(p)
                    else:
                        queues[quantum_da_fila][1].append(p)
                # se tem I/O
                # bloqueia o processo
                else:
                    lista_bloqueados.append(p)
                    processos.remove(p)
                    print('id=msg&value=' + dicionario['process_goes_to_blocked_list'] % (p.nome, str(tempo_utilizado), str(p.io_time)))

        numero_switches = numero_switches + 1

    # lembra de tirar o switch_cost a mais que eu to contando
    current_time = current_time - switch_cost
    print('id=tte&value=' + str(current_time))

if __name__ == '__main__':
    main()



