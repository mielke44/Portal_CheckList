@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Tarefas')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap v-if="!form_view">
        <v-flex>
            <table align='right'>
                <tr>
                    <td>
                        <v-btn color="primary" @click="add()" class='mr-0'>Adicionar tarefa</v-btn>
                    </td>
                </tr>
            </table>
        </v-flex>
        <v-flex xs12>
            <v-expansion-panel>
                <v-expansion-panel-content v-for='(t,i) in pagination_result'>
                    <div slot="header">
                        <v-layout row wrap fill-height align-center>
                            <v-flex xs6>
                                @{{t.name}}
                            </v-flex>
                            <v-flex xs3>
                                @{{t.type}}
                            </v-flex>
                        </v-layout>
                    </div>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-flex xs6 class='font-weight-bold'>
                                Descrição:
                            </v-flex>
                            <v-flex xs6>
                                @{{t.description}}
                            </v-flex>
                            <v-flex xs6 class='font-weight-bold'>
                                Responsável Padrão:
                            </v-flex>
                            <v-flex xs6>
                                @{{t.resp_name}}
                            </v-flex>
                            <v-flex xs6 class='font-weight-bold'>
                                Tempo Limite Padrão:
                            </v-flex>
                            <v-flex xs6 v-if="t.limit!=1">
                                @{{t.limit}} Dias
                            </v-flex>
                            <v-flex xs6 v-else>
                                @{{t.limit}} Dia
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
                <v-expansion-panel-content v-if='models.task.list.length==0'>
                    <div slot="header">Nenhuma tarefa foi criada</div>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-flex>
        <v-flex xs12 class='text-xs-center'>
            <v-pagination v-model="pagination" :length="pagination_pages"></v-pagination>
        </v-flex>
    </v-layout>

    <!-- FORM VIEW-->
    <v-layout row wrap v-if="form_view">
        <v-flex s12>
            <v-card>
                <v-container grid-list-xs>
                    <v-form ref='form'>
                        <v-layout row wrap>
                            <v-flex xs12 class='display-3'>
                                Criar Tarefa
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field v-model="form.name" label="Tarefa" required :rules="rules.name" counter='25'></v-text-field>

                            </v-flex>
                            <v-flex xs12>
                                <v-textarea v-model="form.description" label="Descrição" :rules="rules.description"
                                    required counter='300'></v-textarea>

                            </v-flex>
                            <v-flex xs12>
                                <v-autocomplete v-model="form.resp" :items="user_resp_array" item-text="name"
                                    item-value="id" label="Responsável padrão" hide-no-data hide-selected></v-autocomplete>
                            </v-flex>
                            <v-flex xs12>
                                <v-select v-model="form.type" :items="types" item-text="text" item-value="text" :rules="rules.type"
                                    label="Tipo de tarefa" persistent-hint single-line required></v-select>

                            </v-flex>
                            <v-flex xs12 shrink class="pl-2">
                                <v-text-field suffix="Dias" placeholder='Limite de tempo' v-model="form.limit"
                                    single-line type="number"></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-btn @click="store" color="primary">@{{form_texts.button}}</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-form>
                </v-container>
            </v-card>
        </v-flex>
    </v-layout>
</v-container>
</v-card>
</v-flex>
</v-layout>

</v-container>


@endsection

@section('l-js')
<script src='{{asset("sources/tasks.js")}}'></script>
<script src='{{asset("sources/users.js")}}'></script>
<script>
    vue_page = {
        mixins: [sources_tasks, sources_users],
        data() {
            return {
                resp: [],
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
                    resp: [
                        v => !!v || 'Campo obrigtório'
                    ],
                },
                form: {
                    name: '',
                    description: '',
                    type: '',
                    dependences2: [],
                    resp: "",
                    limit: 10,

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
                search: '',
                pagination: 1,
                itemsPage: 20,
            }
        },
        computed: {
            search_data: function () {
                var array = [];
                this.pagination = 1;
                for (t of this.models.task.list) {
                    array.push(app.search_text(this.search, t.name));

                }
                return array;
            },
            search_total: function () {

                var total = 0;
                for (s of this.search_data) {
                    if (s) total++;
                }
                return total;
            },
            pagination_result() {
                var array = [];
                var length = 0;
                var j = 0;
                for (t of this.models.task.list) {
                    if (this.search_data[i]) {
                        j++;
                        if (j > this.itemsPage * (this.pagination - 1)) {

                            length++;
                            array.push(t);
                            if (length == this.itemsPage) break;
                        }
                    }

                }
                return array;
            },
            pagination_pages() {
                var pages = this.search_total / this.itemsPage;
                if (this.search_total % this.itemsPage > 0) pages++;
                return pages;
            }

        },
        methods: {
            add: function () {
                this.form_view = true;
                this.form_texts.title = "Criar tarefa";
                this.form_texts.button = "Criar";
                this.form = {
                    name: '',
                    description: '',
                    type: '',
                    dependences2: [],
                    limit: '',
                }
            },
            store: function () {
                if (this.$refs.form.validate()) {
                    app.confirm("Criando/Alterando Registro!", "Confirmar ação neste Registro?", "green", () => {
                        this.store_model(this.models.task, this.form, (response) => {
                            this.form_view = false;
                            if (this.form.id == "") app.notify("Tarefa criada",
                                "success");
                            else app.notify("Edição salva", "success");
                        })
                    })
                }
            },
            edit: function (task_id) {
                $.ajax({
                    url: "",
                    method: "GET",
                    dataType: "json",
                    data: {
                        id: task_id
                    },
                }).done(response => {
                    this.form_texts.title = "Editar tarefa";
                    this.form_texts.button = "Salvar";
                    this.form = response;
                    if (response.resp.length == 1) {
                        this.form.resp = parseInt(this.form.resp);
                    }
                    this.form_view = true;
                });
            },
            destroy: function (task_id) {
                app.confirm("Remover Registro!", "Deseja remover este Registro?", "red", () => {
                    this.destroy_model(this.models.task, task_id, () => {
                        this.list_model(this.models.list);
                        app.notify("Tarefa removida", "error");
                    })

                })
            },
            getTask: function (id) {
                for (j = 0; j < this.tasks.length; j++) {
                    if (id == this.tasks[j].id) return this.tasks[j]
                }
                return null;
            },
            searching: function (search) {
                this.search = search;
            },
        },
        mounted() {
            this.list_model(this.models.task);
            this.list_model(this.models.user);
            this.list_model(this.models.group);
        }
    };
</script>
@endsection
