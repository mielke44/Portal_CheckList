@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Empregados')


@section('l-content')


<v-container grid-list-lg fill-height>
    <v-layout row wrap>
        <v-flex class='text-xs-right'>
            <v-btn href="{{ route('emp.create') }}" color="primary">Adicionar empregado</v-btn>
        </v-flex>
        <v-flex xs12>
                <v-expansion-panel>
                        <v-expansion-panel-content v-for='em in employees'>
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
                                    <v-flex xs3 class='font-weight-bold'>@{{em.created_at}}</v-flex>
                                    <v-flex xs12 class='text-xs-right'>
                                            <v-btn  color="blue" outline> <v-icon dark class='mr-2'>check</v-icon> Checklist</v-btn>
                                            <v-btn @click="edit(em.id)" color="yellow darken-2" outline><v-icon dark class='mr-2'>edit</v-icon> Editar</v-btn>
                                            <v-btn @click="remove(em.id)" color="red" outline><v-icon dark class='mr-2'>delete</v-icon> Remover</v-btn>    
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
                employees: [
                ]
            }
        },
        methods:{
            edit: function(id){
                location.href="{{ route('employee') }}"+"/edit/"+id;
            },
            remove: function(id){
                $.ajax({
                    method:"DELETE",
                    url:"{{ route('employee') }}"+"/remove/"+id,
                    headers:app.headers,
                success: (Response)=> {
                                console.log(Response.error);
                                if (Response.error == true) {
                                }
                                else {
                                    this.list();
                                }
                            }
                });
                
            },
            list: function(){
            $.ajax({
                url: "{!! route('emp.list') !!}",
                method: "GET",
                dataType: "json"

            }).done(Response => {
                console.log(JSON.stringify(Response));
                this.employees = Response;

            })
        }
        },
        mounted(){
            this.list();
        }

    });
</script>
@endsection
