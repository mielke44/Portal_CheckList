@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Editar Empregado')


@section('l-content')

<v-container grid-list-lg fill-height>
    <v-layout row wrap>
        <v-flex xs12 sm6 offset-sm3>
            <v-card>
                <v-form v-model="valid">
                    <v-card-text>
                        <v-text-field v-model="data.name" :rules="nameRules" label="Name" required></v-text-field>
                        <v-text-field v-model="data.email" :rules="emailRules" label="E-mail" required></v-text-field>
                        <v-text-field v-model="data.cpf" :rules="cpfRules" label="CPF" required></v-text-field>
                        <v-text-field v-model="data.fone" :rules="foneRules" label="Telefone" required></v-text-field>
                    
                        <v-flex xs6>
                            <v-select v-model="data.select" :hint="`${data.select.type}`" 
                            :items="items" item-text="type" label="Tipo" persistent-hint return-object single-line required></v-select>
                        </v-flex>
                        <v-btn @click = "edit" color="primary">Editar empregado</v-btn>
                    </v-card-text>
                    
                </v-form>
            </v-card>
        </v-flex>
    <v-layout>
<v-container>

@endsection

@section('l-js')
<script>
    Vue.component("page", {
        data: () => ({
            data:{
            valid: false,
            name: '{{$eid->name}}',
            email: '{{$eid->email}}',
            cpf: '{{$eid->cpf}}',
            fone: '{{$eid->fone}}',
            select: {type: 'Tipo'},
            },
            items: [
            { type: 'Efetivado'},
            { type: 'Estagiário'}
            ],
            foneRules:[
                v => v.length <= 11 || 'Telefone deve ser válido!'
            ],
            cpfRules:[
                v => !!v || 'CPF é obrigatório!',
                v => v.length <= 11 || 'CPF deve ser válido!'
            ],
            emailRules: [
                v => !!v || 'E-mail é obrigatório!',
                v => /.+@.+/.test(v) || 'E-mail deve ser válido!'
            ],
            nameRules: [
                v => !!v || 'Nome é obrigatório!',
                v => v.length <= 10 || 'Nome deve ter pelo menos 10 caracteres!'
            ],
            valid: false,
            mask: 'cpf',
            value: '123.123.123-12',
            mask: 'phone',
            value: '(12) 12345 - 1234',
        }),
        methods:{
            edit: function(){
                $.ajax({
                dataType: "json",
                method: "POST",
                url: "{!! route('emp.update') !!}",
                data: this.data,
                headers: app.headers,
                error: function (data) {
                            console.log(data.error);
                                if( data.status === 422 ) {
                                    var errors = data.responseJSON;
                                    console.log(errors);
                                    var errors = $.parseJSON(reject.responseText);
                                    $.each(errors, function (key, val) {
                                        $("#" + key + "_error").text(val[0]);
                                    });
                                }
                            },
                        success: function (response) {
                                console.log(response.error);
                                if (response.error == true) {
                                }
                                else {
                                    window.location = "{!! route('employee') !!}";
                                }
                            }
            })   
            }
        }
    })
</script>
@endsection