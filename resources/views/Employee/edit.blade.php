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
                        <v-text-field v-model="name" :rules="nameRules" label="Name" required></v-text-field>
                        <v-text-field v-model="email" :rules="emailRules" label="E-mail" required></v-text-field>
                        <v-text-field v-model="cpf" :rules="cpfRules" label="CPF" required></v-text-field>
                        <v-text-field v-model="fone" :rules="foneRules" label="Telefone" required></v-text-field>
                    
                        <v-flex xs6>
                            <v-select v-model="select" :hint="`${select.type}`" 
                            :items="items" item-text="type" label="Select" persistent-hint return-object single-line required></v-select>
                        </v-flex>
                        <v-btn @click = "edit" color="primary">Adicionar empregado</v-btn>
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
            items: [
            { type: 'Efetivado'},
            { type: 'Estagiário'}
            ],
            },
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
            mask: 'cpf',
            value: '123.123.123-12',
            mask: 'phone',
            value: '(12) 12345 - 1234',
        }),
        method:{
            edit: function(){

            }
        }
    })
</script>
@endsection