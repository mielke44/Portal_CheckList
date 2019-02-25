@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Lista de tarefas')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap v-if="!form_view">
        <v-flex class='text-xs-right'>
            <v-btn color="primary" @click="add()">Adicionar Lista de Tarefas</v-btn>
        </v-flex>
        <v-flex xs12>
            <v-expansion-panel>
                <v-expansion-panel-content v-for='(l,i) in clists' v-if='search_data[i]'>
                    <div slot="header">
                        <v-layout row wrap fill-height align-center>
                            <v-flex xs6 class='font-weight-bold'>
                                @{{l.name}}
                            </v-flex>
                        </v-layout>
                    </div>
                    <v-container grid-list-xs>
                        <v-layout class="pb-2" row wrap>
                            <v-flex xs3 fill-height class='font-weight-bold'>
                                Perfil:
                            </v-flex>
                            <v-layout row wrap>
                                <v-flex xs9 v-for='pf in l.profile'>
                                    @{{pf.name}}
                                </v-flex>
                            </v-layout>
                        </v-layout>
                        <v-divider></v-divider>
                        <v-layout class="pt-2" row wrap>
                            <v-flex xs3 class='font-weight-bold' v-if="l.dependences.length>0">
                                Tarefas:
                            </v-flex>
                            <v-flex class="pa-0 ma-0" xs9>
                                <v-layout row wrap>
                                    <template v-for="d in l.dependences">
                                        <v-flex xs6>
                                            @{{d.name}}
                                        </v-flex>
                                        <v-flex xs6 class='caption'>
                                            @{{d.desc}}
                                        </v-flex>
                                    </template>
                                </v-layout>
                            </v-flex>
                            <v-flex xs12 class='text-xs-right'>
                                <v-btn @click="edit(l.id)" color="yellow darken-2" outline>
                                    <v-icon dark class='mr-2'>edit</v-icon> Editar
                                </v-btn>
                                <v-btn @click="destroy(l.id)" color="red" outline>
                                    <v-icon dark class='mr-2'>delete</v-icon> Remover
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-expansion-panel-content>
                <v-expansion-panel-content v-if='clists.length==0'>
                    <div slot="header">Nenhuma lista de tarefas foi criada</div>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-flex>
    </v-layout>

    <!--FORM-->
    <v-layout row wrap v-if="form_view">
        <v-flex s12>
            <v-card>
                <v-container grid-list-xs>
                    <div class='display-3'>@{{form_texts.title}}</div>
                    <v-form ref='form'>
                        <v-card-text>
                            <v-text-field v-model="form.name" label="Nome" required :rules="rules.name" counter='25'></v-text-field>
                            <v-autocomplete v-model="form.profile_id" :items="profile" label="Perfis" item-text="name"
                                item-value="id" :rules="rules.prof_id" multiple hide-no-data hide-selected></v-autocomplete>
                            <div class='headline mb-2 mt-2'>Dependências</div>
                            <v-layout row wrap>
                                <v-flex xs6>
                                    <v-treeview :open='form.dependences' :items='task_tree' :active.sync='task_tree_active'
                                        activatable active-class='extra-treeview'>
                                        <template slot='prepend' slot-scope="{ item, open, leaf }">
                                            <div>
                                                <v-checkbox color='primary' v-model='form.dependences' :value='item.id'
                                                    @change='set_dependence_tree(item.id,item.tree)'></v-checkbox>
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
                teste: true,
                clists: [],
                profile: [],
                task: [],
                task_tree: [],
                task_tree_active: [],
                tree_test: [],
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
                    dep: [
                        v => !!v || 'Campo obrigtório'
                    ],
                    prof_id: [
                        v => !!v || 'Campo obrigtório'
                    ],
                },
                form: {
                    id: "",
                    name: '',
                    profile_id: '',
                    dependences: []
                },
                search:''
            }
        },
        computed: {
            task_tree_selected: function () {
                if (this.task_tree_active.length == 0) return 0;
                return this.getTask(this.task_tree_active[0]);
            },
            search_data: function () {
                var array = [];
                for (l of this.clists) {
                    array.push(app.search_text(this.search, l.name));
                }
                return array;
            }
        },
        methods: {
            add: function () {
                this.form_view = true;
                this.form_texts.title = "Criar lista de tarefas";
                this.form_texts.button = "Criar";
                this.form = {
                    id: "",
                    name: '',
                    profile_id: '',
                    dependences: [],
                }
            },
            store: function () {
                //alert(JSON.stringify(this.profile));
                if (this.$refs.form.validate()) {
                    app.confirm("Criando/Alterando Registro!", "Confirmar ação deste Registro?", "green", () => {
                        $.ajax({
                            url: "{{route('checklist.store')}}",
                            method: "POST",
                            dataType: "json",
                            headers: app.headers,
                            data: this.form,
                            success: (response) => {
                                this.list();
                                this.form_view = false;
                                if (this.form.id == "") app.notify(
                                    "Lista de tarefa criada com sucesso!",
                                    "success");
                                else app.notify("Edição salva", "success");
                            }
                        });
                    })
                }
            },
            list: function () {
                $.ajax({
                    url: "{{route('checklist.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.clists = response;
                });
            },
            list_profile: function () {
                $.ajax({
                    url: "{{route('profile.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.profile = response;
                });
            },
            list_task: function () {
                $.ajax({
                    url: "{{route('task.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.task = response;
                });
            },
            set_dependence(id, set) {
                position = this.positionDependenceSet(id);
                if (!set & position > -1) this.form.dependences.splice(position, 1);
                else if (set & position == -1) {
                    this.form.dependences.push(parseInt(id));
                }
            },
            set_dependence_tree(id, tree) {
                set = this.positionDependenceSet(id) > -1 ? true : false;
                if (set) {
                    ids = tree.split(';')
                    for (id of ids) {
                        if (id != '') this.set_dependence(id, set);
                    }
                } else {
                    task = this.getTask(id);
                    for (d of task.dependence) {
                        this.set_dependence(d.task_id, set);
                        this.set_dependence_tree(d.task_id, "");
                    }

                }
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
            edit: function (id) {
                $.ajax({
                    url: "{{route('checklist.edit')}}",
                    method: "GET",
                    dataType: "json",
                    data: {
                        id: id
                    },
                }).done(response => {
                    //alert(JSON.stringify(response));
                    this.form_texts.title = "Editar Lista";
                    this.form_texts.button = "Salvar";
                    this.form = response;
                    this.form_view = true;
                });
            },
            destroy: function (id) {
                app.confirm("Remover Registro!", "Deseja remover este Registro?", "red", () => {
                    $.ajax({
                        url: "{{route('checklist.destroy')}}",
                        method: "DELETE",
                        dataType: "json",
                        headers: app.headers,
                        data: {
                            id: id
                        },
                        success: (response) => {
                            this.list();
                            app.notify("Lista de tarefa removida", "error");
                        }
                    });
                })
            },
            remove(item) {
                const index = this.friends.indexOf(item.name)
                if (index >= 0) this.friends.splice(index, 1)
            },
            positionDependenceSet: function (id) {
                for (i = 0; i < this.form.dependences.length; i++) {
                    if (id == this.form.dependences[i]) return i;
                }
                return -1;
            },
            isDependenceSet: function (id) {
                for (i = 0; i < this.form.dependences.length; i++) {
                    if (id == this.form.dependences[i]) return true;
                }
                return false;
            },
            getTask: function (id) {
                for (j = 0; j < this.task.length; j++) {
                    if (id == this.task[j].id) return this.task[j]
                }
                return null;
            },
            mounted: function () {
                app.setMenu('checklist');
            },
            searching: function (search) {
                    this.search = search;
                },
        },
        watch: {
            isUpdating(val) {
                if (val) {
                    setTimeout(() => (this.isUpdating = false), 3000)
                }
            },
        },
        mounted() {
            this.list();
            this.list_task();
            this.get_task_tree();
            this.list_profile();
            setTimeout(() => {
                app.screen = 3
            }, 1);
        }
    });
</script>
@endsection
