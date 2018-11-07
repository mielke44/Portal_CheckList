@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Empregados')


@section('l-content')


<v-container grid-list-lg fill-height>
    <v-layout row wrap>
        <v-flex class='text-xs-right'>
            <v-btn color="primary">Adicionar empregado</v-btn>
        </v-flex>
        <v-flex xs12>
                <v-expansion-panel>
                        <v-expansion-panel-content v-for='em in employess'>
                            <div slot="header">
                                <v-layout row wrap fill-height align-center>
                                    <v-flex xs6>
                                        @{{em.name}}
                                    </v-flex>
                                    <v-flex xs3>
                                        @{{em.profile}}
                                    </v-flex>
                                    <v-flex xs3 class='text-xs-right'>
                                        <span class='mr-2'>@{{em.checks}}/@{{em.list}}</span>
                                        <v-progress-circular rotate="-90" :value="em.checks/em.list*100" color="primary"
                                            class='mr-2' width='7'></v-progress-circular>
                                    </v-flex>
                                </v-layout>
                            </div>
                            <v-container grid-list-xs>
                                <v-layout row wrap>
                                    <v-flex xs3>CPF:</v-flex>
                                    <v-flex xs3 class='font-weight-bold'>@{{em.cpf}}</v-flex>
                                    <v-flex xs3>E-mail:</v-flex>
                                    <v-flex xs3 class='font-weight-bold'>@{{em.email}}</v-flex>
                                    <v-flex xs3>Telefone:</v-flex>
                                    <v-flex xs3 class='font-weight-bold'>@{{em.phone}}</v-flex>
                                    <v-flex xs3>Data admissão</v-flex>
                                    <v-flex xs3 class='font-weight-bold'>@{{em.date}}</v-flex>
                                    <v-flex xs12 class='text-xs-right'>
                                        <v-btn color="blue" outline> <v-icon dark class='mr-2'>check</v-icon> Checklist</v-btn>
                                        <v-btn color="yellow darken-2" outline><v-icon dark class='mr-2'>edit</v-icon> Editar</v-btn>
                                        <v-btn color="red" outline><v-icon dark class='mr-2'>delete</v-icon> Remover</v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-container>

        </v-flex>
    </v-layout>
</v-container>


@endsection

@section('l-js')
<script>
    Vue.component("page", {
        data() {
            return {
                employess: [{
                        id: 1,
                        name: "Christiano Oishi de Carvalho",
                        profile: "Estagiário",
                        cpf: "079.323.579.00",
                        email: "oishi.chris@gmail.com",
                        phone: "41 9 990 1427",
                        date: "04/06/2018",
                        checks: 2,
                        list: 22
                    },
                    {
                        id: 1,
                        name: "Christiano Oishi de Carvalho",
                        profile: "Estagiário",
                        cpf: "079.323.579.00",
                        email: "oishi.chris@gmail.com",
                        phone: "41 9 990 1427",
                        date: "04/06/2018",
                        checks: 2,
                        list: 22
                    }
                ]
            }
        },
    });
</script>
@endsection