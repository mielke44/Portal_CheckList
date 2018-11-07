@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Tarefas')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap v-if="!form_view">
        <v-flex class='text-xs-right'>
            <v-btn color="primary" @click="form_view=true">Adicionar tarefa</v-btn>
        </v-flex>
        <v-flex xs12>
            <v-expansion-panel>
                <v-expansion-panel-content v-for='t in tasks'>
                    <div slot="header">
                        <v-layout row wrap fill-height align-center>
                            <v-flex xs6>
                                @{{t.name}}
                            </v-flex>
                            <v-flex xs3>
                                @{{t.type}}
                            </v-flex>
                            <v-flex xs3 class='text-xs-right'>
                                @{{t.dependence.length}}
                            </v-flex>
                        </v-layout>
                    </div>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-flex xs3 class='font-weight-bold'>
                                Descrição:
                            </v-flex>
                            <v-flex xs9>
                                @{{t.description}}
                            </v-flex>
                            <v-flex xs3 class='font-weight-bold' v-if="t.dependence.length>0">
                                Dependencias:
                            </v-flex>
                            <v-flex xs9>
                                <template v-for="d in t.dependence">@{{d.name}},</template>
                            </v-flex>
                            <v-flex xs12 class='text-xs-right'>
                                <v-btn @click="" color="yellow darken-2" outline>
                                    <v-icon dark class='mr-2'>edit</v-icon> Editar
                                </v-btn>
                                <v-btn @click="" color="red" outline>
                                    <v-icon dark class='mr-2'>delete</v-icon> Remover
                                </v-btn>
                            </v-flex>
                        </v-layout>

                    </v-container>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-flex>

    </v-layout>

    <v-layout row wrap v-if="form_view">
        <v-flex s12>
            <v-card>
                <v-container grid-list-xs>
                    <div class='display-3'>Criar tarefa</div>
                    <v-form ref='form'>
                        <v-card-text>
                            <v-text-field v-model="form.name" label="Tarefa" required :rules="rules.name" counter='25'></v-text-field>
                            <v-textarea v-model="form.description" label="Descrição" :rules="rules.description" required counter='300'></v-textarea>
                            <v-select v-model="form.type" :items="types" item-text="text" :rules="rules.type" label="Tipo de tarefa"
                                persistent-hint return-object single-line required></v-select>
                                <v-select v-model="form.dependences" :items="dependences" item-text="text" label="Dependencias"
                                persistent-hint return-object multiple required></v-select>
                            <v-btn @click="save" color="primary">Adicionar Tarefa</v-btn>
                        </v-card-text>

                    </v-form>
                </v-container>

            </v-card>
        </v-flex>
    </v-layout>

</v-container>


@endsection

@section('l-js')
<script>
    Vue.component("page", {
        data() {
            return {
                tasks: [{
                        id: 1,
                        name: "Fazer crachá",
                        type: "Solicitação",
                        description: "Criar crachá",
                        dependence: [{
                                task_id: 1,
                                name: "oi"
                            },
                            {
                                task_id: 1,
                                name: "oi"
                            },
                            {
                                task_id: 1,
                                name: "oi"
                            },
                        ]
                    },
                    {
                        id: 1,
                        name: "Identidade",
                        type: "Documento",
                        description: "Solicitar documento",
                        dependence: [{
                                task_id: 1,
                                name: "oi"
                            },
                            {
                                task_id: 1,
                                name: "oi"
                            },
                            {
                                task_id: 1,
                                name: "oi"
                            },
                        ]
                    },
                ],

                form_view: false,
                rules:{
                    name:[
                        v=>!!v||'Campo obrigtório',
                        v => (v && v.length <= 25) || 'Máximo 25 caracteres'
                    ],
                    description:[
                        v=>!!v||'Campo obrigtório',
                        v => (v && v.length <= 300) || 'Máximo 300 caracteres'
                    ],
                    type:[
                        v=>!!v||'Campo obrigtório'
                    ],
                },
                form: {
                    name: '',
                    description: '',
                    type: '',
                    dependences: ''
                },
                types: [{
                        text: "Solicitação",
                    },
                    {
                        text: "Documento",
                    },
                ],
                dependences: [{
                        text: "Solicitação",
                    },
                    {
                        text: "Documento1",
                    },
                    {
                        text: "Documento2",
                    },
                    {
                        text: "Documento3",
                    },
                    {
                        text: "Documento4",
                    },
                    {
                        text: "Documento5",
                    },
                    {
                        text: "Documento6",
                    },
                    {
                        text: "Documento7",
                    },
                    {
                        text: "Documento8",
                    },
                    {
                        text: "Documento9",
                    },
                    {
                        text: "Documento10",
                    },

                ]
            }
        },
        methods: {
            save: function () {
                this.$refs.form.validate();
            }
        }
    });
</script>
@endsection
