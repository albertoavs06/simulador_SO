# -*- coding: utf-8 -*-

# comeca o tempo em 0
current_time = 0

# arranjar um jeito de pegar o quantum
quantum = 5
switch_cost = 1
io_operation_time = 5
processing_time_until_io = 1

# lista de processos
processos = []
lista_bloqueados = []

json_string = [ {"nome":"A","tempo":"10","tipo":"cpu","valor":""},
                {"nome":"B","tempo":"21","tipo":"io","valor":""},
                {"nome":"C","tempo":"13","tipo":"cpu","valor":""},
                {"nome":"D","tempo":"15","tipo":"cpu","valor":""},
                {"nome":"E","tempo":"30","tipo":"io","valor":""},
                {"nome":"F","tempo":"25","tipo":"cpu","valor":""},
                {"nome":"G","tempo":"15","tipo":"cpu","valor":""},
                {"nome":"H","tempo":"16","tipo":"io","valor":""},
                {"nome":"I","tempo":"17","tipo":"cpu","valor":""},
                {"nome":"J","tempo":"18","tipo":"cpu","valor":""}]

class Processo:
    def __init__(self, nome, tempo, tipo):
        self.nome = nome
        self.tempo = int(tempo)
        self.tipo = tipo
        self.io_time = 0

    def to_string(self):
        return "[" + self.nome + ":" + str(self.tempo) + ":" + str(self.io_time) + "]"

def atualiza_lista_bloqueados(tempo_utilizado):
    # para cada processo, eu preciso atualizar o tempo de I/O deles
    for processo in lista_bloqueados:
        processo.io_time = processo.io_time - (tempo_utilizado + switch_cost)

        if(processo.io_time < 0):
            processo.io_time = 0
            lista_bloqueados.remove(processo)
            processos.append(processo)
            print('processo ' + processo.nome + ' volta para a lista de pronto')

def main():
    global processos
    global lista_bloqueados
    global current_time

    # itera pelo json, adicionando processos na lista
    for s in json_string:
        p = Processo(s['nome'], s['tempo'], s['tipo'])
        processos.append(p)

    # algoritimo do round robin, enquanto ainda ha' processos na lista
    while len(processos) > 0 or len(lista_bloqueados) > 0:

        # imprime a lista de processos
        msg = "prontos "
        for processo in processos:
            msg = msg + processo.to_string() + " "
        msg = msg + " bloqueados "
        for processo in lista_bloqueados:
            msg = msg + processo.to_string() + " "
        print(msg)

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
            aux_quantum = processing_time_until_io  # o quantum dele e' o tempo ate ele fazer I/O
            aux_io = io_operation_time              # o tempo de I/O dele e' o tempo de um I/O

        # se o processo for do tipo CPU bound
        else:
            aux_quantum = quantum   # ele vai executar o quantum inteiro
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
            print('executa processo ' + p.nome + ' por ' + str(tempo_utilizado) + ' e termina ele')
        # senao coloca ele no fim da lista
        else:
            if(p.tipo == "cpu"):    # vai para o fim da lista
                processos.remove(p)
                processos.append(p)
                print('executa processo ' + p.nome + ' por ' + str(tempo_utilizado) + ' e vai para o fim da lista de pronto')
            else:                   # vai para a lista de bloqueados
                lista_bloqueados.append(p)
                processos.remove(p)
                print('executa processo ' + p.nome + ' por ' + str(tempo_utilizado) + ' e vai para a lista de bloqueado por ' + str(p.io_time))

    # lembra de tirar o switch_cost a mais que eu to contando
    current_time = current_time - switch_cost
    print('Tempo total de execucao: ' + str(current_time))

if __name__ == '__main__':
    main()

