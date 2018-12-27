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
                        <v-btn-toggle v-model="task_view_mode">
                            <v-btn flat>
                                <v-icon>list</v-icon>
                            </v-btn>
                            <v-btn flat>
                                <v-icon>timeline</v-icon>
                            </v-btn>
                        </v-btn-toggle>
                    </td>
                    <td>
                        <v-btn color="primary" @click="add()" class='mr-0'>Adicionar tarefa</v-btn>
                    </td>
                </tr>
            </table>


        </v-flex>
        <v-flex xs12>
            <template v-if='task_view_mode==1'>
                <v-card>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-flex xs6>
                                <v-treeview open-all :items='task_tree' :active.sync='task_tree_active' activatable
                                    active-class='extra-treeview'>
                                </v-treeview>
                            </v-flex>
                            <v-flex xs6 v-if='task_tree_selected!=0'>
                                <div class='headline mb-2 mt-2'>@{{task_tree_selected.name}}</div>
                                <v-layout row wrap>
                                    <v-flex xs6>Tipo:</v-flex>
                                    <v-flex xs6>@{{task_tree_selected.type}}</v-flex>
                                    <v-flex xs6>Responsável padrão:</v-flex>
                                    <v-flex xs6>@{{task_tree_selected.resp_name}}</v-flex>
                                </v-layout>
                                <v-divider></v-divider>
                                <p class='mt-2'>@{{task_tree_selected.description}}</p>
                                <v-divider></v-divider>
                                <div class='mt-2'>
                                    <v-btn @click="edit(task_tree_selected.id)" color="yellow darken-2" outline>
                                        <v-icon dark class='mr-2'>edit</v-icon> Editar
                                    </v-btn>
                                    <v-btn @click="destroy(task_tree_selected.id)" color="red" outline>
                                        <v-icon dark class='mr-2'>delete</v-icon> Remover
                                    </v-btn>
                                </div>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
            </template>
            <v-expansion-panel v-if='task_view_mode==0'>
                <v-expansion-panel-content v-for='(t,i) in pagination_result'>
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

                            <template v-if="t.dependence.length>0">
                                <v-flex xs3 class='font-weight-bold'>
                                    Dependentes:
                                </v-flex>
                                <v-flex xs9>
                                    <v-layout row wrap>
                                        <v-flex xs12 v-for="d in t.dependence">@{{d.name}}</v-flex>
                                    </v-layout>
                                </v-flex>
                            </template>
                            <template v-if="t.dependence2.length>0">
                                <v-flex xs3 class='font-weight-bold'>
                                    Dependências:
                                </v-flex>
                                <v-flex xs9>
                                    <v-layout row wrap>
                                        <v-flex xs12 v-for="d in t.dependence2">@{{getTreeAsc(d.task_id)}}</v-flex>
                                    </v-layout>
                                </v-flex>
                            </template>
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
                <v-expansion-panel-content v-if='tasks.length==0'>
                    <div slot="header">Nenhuma tarefa foi criada</div>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-flex>
        <v-flex xs12 class='text-xs-center'>
            <v-pagination v-model="pagination" :length="pagination_pages" v-if='task_view_mode==0'></v-pagination>
        </v-flex>
    </v-layout>
    <!-- FORM VIEW-->
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
                            <v-autocomplete v-model="form.resp" :items="resp" color="black" item-text="name" item-value="id"
                                label="Responsável padrão (pode alterar posteriormente)" hide-no-data hide-selected></v-autocomplete>
                            <v-select v-model="form.type" :items="types" item-text="text" item-value="text" :rules="rules.type"
                                label="Tipo de tarefa" persistent-hint single-line required></v-select>
                            <div class='headline mb-2 mt-2'>Limite de tempo</div>
                            <v-layout row wrap>
                                <v-flex xs9 class="pr-5">
                                    <v-slider v-model="form.limit" :max='31' :min='0' thumb-color="primary" label="Dias"
                                    inverse-label thumb-label="always">
                                        <v-icon slot="prepend" color="primary" @click="form.limit-=1">remove</v-icon>
                                        <v-icon slot="append" color="primary" @click="form.limit+=1">add</v-icon>
                                    </v-slider>
                                </v-flex>
                                <v-flex xs1 shrink class="pl-2">
                                        <v-text-field suffix="Dias" v-model="form.limit" single-line type="number"></v-text-field>
                                </v-flex>
                            </v-layout>
                            <div class='headline mb-2 mt-2'>Dependências</div>
                            <v-layout row wrap>
                                <v-flex xs6>
                                    <v-treeview :open='task_tree_opened' :items='task_tree' :active.sync='task_tree_active'
                                        activatable active-class='extra-treeview'>
                                        <template slot='prepend' slot-scope="{ item, open, leaf }">
                                            <div>
                                                <v-checkbox color='primary' v-model='form.dependences2' :value='item.id'
                                                    @change='set_dependence_tree(item.id,2)'></v-checkbox>
                                            </div>
                                        </template>
                                    </v-treeview>
                                </v-flex>
                                <v-flex xs6>
                                    <div class='headline mb-2 mt-2'>@{{task_tree_selected.name}}</div>
                                    @{{task_tree_selected.description}}
                                </v-flex>
                            </v-layout>
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
                resp: [],
                task_tree: [],
                task_tree_active: [],
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
                    dependences2: [],
                    resp: '',
                    limit:'',

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
                task_view_mode: 0,
                pagination: 1,
                itemsPage: 20,
            }
        },
        watch: {
            "form.dependences2": function () {
                if (this.isDependenceSet(this.form.id)) {
                    this.set_dependence(this.form.id, false);
                    app.notify("A Tarefa não pode depender dela mesma", "red");
                }

            }
        },
        computed: {
            task_tree_selected: function () {
                if (this.task_tree_active.length == 0) return 0;
                return this.getTask(this.task_tree_active[0]);
            },
            task_tree_opened: function () {
                open = []
                for (d of this.form.dependences2) {
                    open.push(d);
                    open = open.concat(this.open_tree(d));
                }
                return open;
            },
            search_data: function () {
                var array = [];
                this.pagination = 1;
                for (t of this.tasks) {
                    array.push(app.search_text(this.search,t.name));

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
                for (var i = 0; i < this.tasks.length; i++) {
                    if (this.search_data[i]) {
                        j++;
                        if ( j > this.itemsPage * (this.pagination-1)) {

                            length++;
                            array.push(this.tasks[i]);
                            if(length==this.itemsPage)break;
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
                    id: "",
                    name: '',
                    description: '',
                    type: '',
                    dependences2: [],
                    limit:'',
                }
            },
            store: function () {
                if (this.$refs.form.validate()) {
                    app.confirm("Criando/Alterando Registro!", "Confirmar ação neste Registro?", "green", () => {
                        $.ajax({
                            url: "{{route('task.store')}}",
                            method: "POST",
                            dataType: "json",
                            headers: app.headers,
                            data: this.form,
                            success: (response) => {
                                this.tasks.length=0;
                                this.task_tree.length=0;
                                this.list();
                                this.form_view = false;
                                if (this.form.id == "") app.notify("Tarefa criada",
                                    "success");
                                else app.notify("Edição salva", "success");
                            }
                        });
                    })
                }
            },
            list: function () {
                $.ajax({
                    url: "{{route('task.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    for(r of response)this.tasks.push(r);
                    this.get_task_tree();
                });
            },
            admin_list() {
                $.ajax({
                    url: "{{route('admin.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.resp = response['resp_list'];
                    for (i = 0; i < response['admin_list'].length; i++) {
                        this.resp.push(response['admin_list'][i]);
                    }
                    this.resp.push(response['default']);
                    $.ajax({
                        url: "{{route('group.list')}}",
                        method: "GET",
                        dataType: "json",
                    }).done(response =>{
                        for(r of response){
                            r.id='group'+r.id;
                            this.resp.push(r);
                        }
                    })
                })
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
                    this.form.resp=response.resp;
                    this.form_view = true;
                });
            },
            destroy: function (task_id) {
                app.confirm("Remover Registro!", "Deseja remover este Registro?", "red", () => {
                    $.ajax({
                        url: "{{route('task.destroy')}}",
                        method: "DELETE",
                        dataType: "json",
                        headers: app.headers,
                        data: {
                            id: task_id
                        },
                        success: (response) => {
                            this.tasks.length=0;
                            this.task_tree.length=0;
                            this.list();
                            this.get_task_tree();
                            this.task_tree_active = '';
                            app.notify("Tarefa removida", "error");
                        }
                    });
                })
            },
            get_task_tree: function () {
                $.ajax({
                    url: "{{route('task.tree')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.task_tree = response;
                });
            },
            getTask: function (id) {
                for (j = 0; j < this.tasks.length; j++) {
                    if (id == this.tasks[j].id) return this.tasks[j]
                }
                return null;
            },
            open_tree: function (task_id) {
                open = [];
                t = this.getTask(task_id);
                for (dd of t.dependence2) {
                    open.push(dd.task_id);
                    open = open.concat(this.open_tree(dd.task_id));

                }
                return open;
            },
            set_dependence(id, set) {
                position = this.positionDependenceSet(id);
                if (!set & position > -1) this.form.dependences2.splice(position, 1);
                else if (set & position == -1) {
                    this.form.dependences2.push(parseInt(id));
                }
            },
            positionDependenceSet: function (id) {
                for (i = 0; i < this.form.dependences2.length; i++) {
                    if (id == this.form.dependences2[i]) return i;
                }
                return -1;
            },
            set_dependence_tree(id, dir) {
                var t = this.getTask(id);
                if (dir == 1 || dir == 2)
                    for (db of t.dependence) {
                        this.set_dependence(db.task_id, false);
                        this.set_dependence_tree(db.task_id, 1)

                    }


                if (dir == 0 || dir == 2)
                    for (dt of t.dependence2) {
                        //alert(dt.task_id);
                        this.set_dependence(dt.task_id, false);
                        this.set_dependence_tree(dt.task_id, 0)
                    }
            },
            isDependenceSet: function (id) {
                for (i = 0; i < this.form.dependences2.length; i++) {
                    if (id == this.form.dependences2[i]) return true;
                }
                return false;
            },
            getTreeAsc(id) {
                var t = this.getTask(id);
                if (t.dependence2.length > 0) {
                    return this.getTreeAsc(t.dependence2[0].task_id) + " > " + t.name;
                } else return t.name;

            },
            searching: function (search) {
                this.search = search;
                this.task_view_mode = 0;
            },
            mounted: function () {
                app.setMenu('task');
            }
        },
        mounted() {
            this.admin_list();
            this.list();
        }
    });
</script>
@endsection
