@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Adicionar Empregado')


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
                            <v-select v-model="select" :hint="`${select.type}`" 
                            :items="items" item-text="type" label="Select" persistent-hint return-object single-line required></v-select>
                        </v-flex>
                        <v-btn @click="save" color="primary">Adicionar empregado</v-btn>
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
            
            valid: false,
            data:{
            name: '',
            email: '',
            cpf: '',
            fone: '',
            select: {type: 'Tipo'},
            items: [
            { type: 'Efetivado'},
            { type: 'Estagiário'}
            ]
            },
            nameRules: [
                v => !!v || 'Nome é obrigatório!',
                v => v.length <= 10 || 'Nome deve ter pelo menos 10 caracteres!'
            ],
            emailRules: [
                v => !!v || 'E-mail é obrigatório!',
                v => /.+@.+/.test(v) || 'E-mail deve ser válido!'
            ],
            foneRules:[
                v => v.length <= 11 || 'Telefone deve ser válido!'
            ],
            cpfRules:[
                v => !!v || 'CPF é obrigatório!',
                v => v.length <= 11 || 'CPF deve ser válido!'
            ],
            mask: 'cpf',
            value: '123.123.123-12',
            mask: 'phone',
            value: '(12) 12345 - 1234',
            
        }),
    method:{
        save: function(){
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "{!! route('client-store') !!}",
                data: formData,
                headers: app.headers,
                processData: false,
                contentType: false,
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
                                    window.location = "{!! route('client-list') !!}";
                                }
                            }
            })   
        }
    }
})
</script>
@endsection