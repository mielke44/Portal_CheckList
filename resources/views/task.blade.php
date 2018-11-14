@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Tarefas')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap v-if="!form_view">
        <v-flex class='text-xs-right'>
            <v-btn color="primary" @click="add()">Adicionar tarefa</v-btn>
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
                                <v-btn @click="edit(t.id)" color="yellow darken-2" outline>
                                    <v-icon dark class='mr-2'>edit</v-icon> Editar
                                </v-btn>
                                <v-btn @click="destroy(t.id)" color="red" outline>
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
                    <div class='display-3'>@{{form_texts.title}}</div>
                    <v-form ref='form'>
                        <v-card-text>
                            <v-text-field v-model="form.name" label="Tarefa" required :rules="rules.name" counter='25'></v-text-field>
                            <v-textarea v-model="form.description" label="Descrição" :rules="rules.description"
                                required counter='300'></v-textarea>
                            <v-select v-model="form.type" :items="types" item-text="text" item-value="text" :rules="rules.type"
                                label="Tipo de tarefa" persistent-hint single-line required></v-select>
                            <v-select v-model="form.dependences" :items="tasks" item-text="name" item-value="id" label="Dependencias"
                                persistent-hint multiple required></v-select>
                            <v-btn @click="store" color="primary">@{{form_texts.button}}</v-btn>
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
        props: {
            screen: String
        },
        data() {
            return {
                tasks: [],
                form_view: false,
                form_texts: {
                    title: "",
                    button: ""
                },
                rules: {
                    name: [
                        v => !!v || 'Campo obrigtório',
                        v => (v && v.length <= 25) || 'Máximo 25 caracteres'
                    ],
                    description: [
                        v => !!v || 'Campo obrigtório',
                        v => (v && v.length <= 300) || 'Máximo 300 caracteres'
                    ],
                    type: [
                        v => !!v || 'Campo obrigtório'
                    ],
                },
                form: {
                    id: "",
                    name: '',
                    description: '',
                    type: '',
                    dependences: ''
                },
                types: [{
                        text: "Solicitação",
                        value: "1",
                    },
                    {
                        text: "Documento",
                        value: "2",
                    },
                ],
            }
        },
        methods: {
            add: function () {
                this.form_view = true;
                this.form_texts.title = "Criar tarefa";
                this.form_texts.button = "Criar";
                this.form = {
                    id: "",
                    name: '',
                    description: '',
                    type: '',
                    dependences: ''
                }
            },
            store: function () {
                if (this.$refs.form.validate()) {
                    $.ajax({
                        url: "{{route('task.store')}}",
                        method: "POST",
                        dataType: "json",
                        headers: app.headers,
                        data: this.form,
                        success: (response) => {
                            this.list();
                            this.form_view = false;
                            if(this.form.id=="")app.notify("Tarefa criada","success");
                            else app.notify("Edição salva","success");
                        }
                    });
                }
            },
            list: function () {
                $.ajax({
                    url: "{{route('task.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.tasks = response;
                });
            },
            edit: function (task_id) {
                $.ajax({
                    url: "{{route('task.edit')}}",
                    method: "GET",
                    dataType: "json",
                    data: {
                        id: task_id
                    },
                }).done(response => {
                    this.form_texts.title = "Editar tarefa";
                    this.form_texts.button = "Salvar";
                    this.form = response;
                    this.form_view = true;
                });
            },
            destroy: function (task_id) {
                $.ajax({
                    url: "{{route('task.destroy')}}",
                    method: "DELETE",
                    dataType: "json",
                    headers: app.headers,
                    data: {
                        id: task_id
                    },
                    success: (response) => {
                        this.list();
                        app.notify("Tarefa removida","error");
                    }
                });
            },
        },
        mounted() {
            this.list();
            setTimeout(()=>{app.screen = 4},1);
        }
    });
</script>
@endsection
