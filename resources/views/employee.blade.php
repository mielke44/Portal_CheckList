@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Empregados')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap>
        <v-flex xs12 md4 class='text-xs-left'>
            <v-select solo append-icon="filter_list" v-model="filtro.type" :items="filtros" item-text="name" item-value="name"
                label="Filtros" persistent-hint :rules='rules.profile'></v-select>
        </v-flex>
        <v-flex xs12 md5>
            <v-select v-model="filtro.site" :items="sites" item-text="complete_name" item-value="id" label="Site" solo
                v-if='filtro.type=="Site"'></v-select>
            <v-select v-model="filtro.profile" :items="models.profile.list" item-text="name" item-value="id" label="Perfil"
                solo v-if='filtro.type=="Perfil"'></v-select>
        </v-flex>
        <v-flex xs12 md3 class='text-xs-right'>
            <v-btn @click="add()" color="primary">Adicionar empregado</v-btn>
        </v-flex>
        <v-flex>
            <v-card height="100%">
                <v-list row wrap class='pa-0'>
                    <v-item-group v-model='view.selected_employee'>
                        <template v-for="(e,i) in models.employee.list">
                            <v-item>
                                <v-list-tile xs12 slot-scope="{ active, toggle }" @click=''>
                                    <v-list-tile-content @click="toggle">
                                        <v-list-title-title :class="active?'red--text':''">
                                            @{{e.name}}
                                        </v-list-title-title>
                                    </v-list-tile-content>
                                    <div style='display:absolute;right:0'>
                                        <v-btn color="primary" flat fab small class='ma-0' @click='destroy(e.id)'>
                                            <v-icon> delete_outline</v-icon>
                                        </v-btn>
                                    </div>
                                </v-list-tile>

                            </v-item>
                            <v-divider></v-divider>
                        </template>
                    </v-item-group>
                    <v-container v-if='models.profile.list.length==0'>Nenhum empregado criado</v-container>

                </v-list>

            </v-card>
        </v-flex>
        <v-flex v-if='view.selected_employee > -1' xs8>
            <v-card height="100%">
                <v-toolbar color="primary" class='headline' dark>
                    @{{selected_employee.name}}
                </v-toolbar>
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs6>
                            Email:
                        </v-flex>
                        <v-flex xs6>
                            @{{selected_employee.email}}
                        </v-flex>
                        <v-flex xs6>
                            Site:
                        </v-flex>
                        <v-flex xs6>
                            @{{get_model(models.site,selected_employee.site).name}}
                        </v-flex>
                        <v-flex xs6>
                            CPF:
                        </v-flex>
                        <v-flex xs6>
                            @{{selected_employee.cpf}}
                        </v-flex>
                        <v-flex xs6>
                            Gestor:
                        </v-flex>
                        <v-flex xs6>
                            @{{get_model(models.user,selected_employee.gestor).name}}
                        </v-flex>
                        <v-flex xs6>
                            Telefone:
                        </v-flex>
                        <v-flex xs6>
                            @{{selected_employee.fone}}
                        </v-flex>
                        <v-flex xs6>
                            Perfil:
                        </v-flex>
                        <v-flex xs6>
                            @{{get_model(models.profile,selected_employee.id).name}}
                        </v-flex>
                        <v-flex xs12>
                            <p class='grey--text'>Lista de tarefas</p>
                        </v-flex>
                        <v-flex xs12 class='text-xs-right'>
                            <v-btn color="yellow darken-3" dark @click='edit'>
                                <v-icon class='mr-2'>edit</v-icon>
                                Editar
                            </v-btn>
                            <v-btn color="info" dark @click='view.new_checklist.show=true'>
                                <v-icon class='mr-2'>playlist_add</v-icon>
                                Adicionar a lista de tarefas
                            </v-btn>

                        </v-flex>
                    </v-layout>
                </v-container>

            </v-card>
        </v-flex>
    </v-layout>


    <!-- EDIT/FORM -->
    <v-dialog row wrap v-model="view.form.show" scrollable>
        <v-card>
            <v-toolbar color="primary" class='headline' dark>
                Empregado
            </v-toolbar>
            <v-card-text>
                <v-container grid-list-xs>
                    <v-form ref='form'>
                        <v-text-field v-model="view.form.data.name" :rules="rules.name" label="Name" required></v-text-field>
                        <v-text-field v-model="view.form.data.email" :rules="rules.email" label="E-mail" required></v-text-field>
                        <v-autocomplete v-model="view.form.data.site" :items="models.site.list" item-text="complete_name"
                            item-value="id" :rules="rules.site" label="Site" persistent-hint required></v-autocomplete>
                        <v-text-field mask="###.###.###-##" return-masked-value="true" v-model="view.form.data.cpf"
                            :rules="rules.cpf" label="CPF" required></v-text-field>
                        <v-autocomplete v-model="view.form.data.gestor" :items="models.user.list" color="black"
                            hide-no-data hide-selected item-text="name" item-value="id" label="Gestor" prepend-icon="assignment_ind"></v-autocomplete>
                        <v-text-field mask="+##(##)#####-####" return-masked-value="true" v-model="view.form.data.fone"
                            :rules="rules.fone" label="Telefone Celular" required></v-text-field>
                        <v-autocomplete v-model="view.form.data.profile_id" :items="models.profile.list" item-text="name"
                            item-value="id" label="Perfil" persistent-hint :rules='rules.profile' required></v-autocomplete>

                    </v-form>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>
                <v-btn color="red" outline dark @click='view.form.show=false'>
                    <v-icon class='mr-2'>close</v-icon>Cancelar
                </v-btn>
                <v-btn color="green" outline dark @click='store'>
                    <v-icon class='mr-2'>save</v-icon>Salvar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog v-model="view.new_checklist.show" scrollable max-width="500px" transition="dialog-transition" v-if='view.selected_employee !=-1'>
        <v-card>
            <v-toolbar color="primary" dark class='headline'>
                Lista de tarefas
            </v-toolbar>
            <v-container grid-list-xs>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-autocomplete v-model="view.new_checklist.id" :items="models.template.list" item-text="name"
                            item-value="id" label="Modelo de lista de tarefa" persistent-hint required></v-autocomplete>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green" outline dark @click=''>
                    <v-icon class='mr-2'>save</v-icon>Adicionar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    @endsection

    @section('l-js')
    <script src='{{asset("sources/profiles.js")}}'></script>
    <script src='{{asset("sources/checklists_template.js")}}'></script>
    <script src='{{asset("sources/tasks.js")}}'></script>
    <script src='{{asset("sources/employees.js")}}'></script>
    <script src='{{asset("plugins/nestable/nestable.js")}}'></script>

    <script>
        vue_page = {
            mixins: [sources_profiles, sources_checklists_template, sources_tasks, sources_employees],
            components: {
                Tree: vueDraggableNestedTree.DraggableTree
            },
            data() {
                return {
                    commentedit: -1,
                    filtros: ['Site', 'Gestor', 'Perfil', 'Todos'],
                    filtro: {
                        type: 'Gestor',
                        site: '',
                        profile: ''
                    },
                    task_tree_active: [],
                    checkbox: [],
                    model_employee: null,
                    dialog: false,
                    dialog2: false,
                    dialog_comment: false,
                    dialog_responsavel: false,
                    employees: [],
                    dependencies: [],
                    profiles: [],
                    checklists: {},
                    tasks: {},
                    check: [],
                    check_size: "",
                    templates: [],
                    sites: [],
                    form_view: false,
                    model_tab_checklist: 0,
                    resp: {},
                    comments: [],
                    gestor_name: [],
                    form_texts: {
                        title: "",
                        button: ""
                    },
                    rules: {
                        name: [
                            v => !!v || 'Campo obrigtório',
                            v => (v && v.length <= 25) || 'Máximo 25 caracteres'
                        ],
                        email: [
                            v => !!v || 'E-mail é obrigatório!',
                            v => /.+@.+/.test(v) || 'E-mail deve ser válido!'
                        ],
                        fone: [
                            v => (v && v.length == 17) || 'Obrigatório 11 caracteres'
                        ],
                        cpf: [
                            v => !!v || 'CPF é obrigatório!',
                            v => (v && v.length == 14) || 'Obrigatório 11 caracteres'
                        ],
                        profile: [
                            v => !!v || 'Campo obrigtório'
                        ],
                        site: [
                            v => !!v || 'Campo obrigtório'
                        ],
                    },
                    form: {
                        id: "",
                        name: '',
                        dependences: '',
                        fone: '',
                        cpf: '',
                        email: '',
                        site: '',
                        profile_id: '',
                        checklist_template_id: '',
                        comment: '',
                        comment_id: '',
                        resp: '',

                    },
                    search: '',

                    view: {
                        selected_employee: -1,
                        form: {
                            show: false,
                            data: {},
                        },
                        new_checklist: {
                            id: "",
                            show: false
                        }
                    }
                }
            },
            computed: {
                selected_employee: function () {
                    return this.models.employee.list[this.view.selected_employee];
                },
                task_tree_selected: function () {
                    if (this.task_tree_active.length == 0) return 0;
                    this.list_comment(this.getCheckByTask(this.task_tree_active[0]).id);
                    return this.getTask(this.task_tree_active[0]);
                },
                employee_selected: function () {
                    if (this.model_employee == null) return 0;
                    return this.employees[this.model_employee];
                },
                checklist_selected: function () {
                    return this.checklists[this.employee_selected.id][this.model_tab_checklist];
                },
                check_tree_selected: function () {
                    return this.getCheckByTask(this.task_tree_selected.id);
                },
                search_data: function () {
                    var array = [];
                    this.model_employee = null;
                    for (e of this.employees) {
                        switch (this.filtro.type) {
                            case 'Gestor':
                                if (e.gestor == app.user.id) {
                                    array.push(app.search_text(this.search, e.name));
                                } else array.push(false);
                                break;
                            case 'Site':
                                if (e.site == this.filtro.site) {
                                    array.push(app.search_text(this.search, e.name));
                                } else array.push(false);
                                break;
                            case 'Perfil':
                                if (e.profile_id == this.filtro.profile) {
                                    array.push(app.search_text(this.search, e.name));
                                } else array.push(false);
                                break;
                            case 'Todos':
                                array.push(app.search_text(this.search, e.name));
                                break;
                        }
                    }
                    return array;
                }
            },
            watch: {
                model_employee: function (val) {
                    if (val != null) {
                        if (!this.checklists.hasOwnProperty(this.employees[val].id)) {
                            this.list_checklist(this.employees[val].id);
                        }
                    }
                },
            },
            methods: {
                add: function () {
                    this.view.form.data = {}
                    Vue.nextTick(() => {
                        this.$refs.form.reset();
                    });
                    this.view.form.show = true;
                },
                store: function () {
                    if (this.$refs.form.validate()) {
                        this.store_model(this.models.employee, this.view.form.data, () => {
                            this.list_model(this.models.employee);
                            this.view.form.show = false;
                            this.notify("Alterações foram salvas", "green");
                        });
                    }

                },
                destroy: function (id) {
                    this.confirm("Confirmação",
                        "Deseja mesmo deletar esse empregado? Todas sas informações e listas de tarefas serão removidas",
                        "red", () => {
                            this.destroy_model(this.models.employee, id, () => {
                                this.notify("Empregado deletado com sucesso", "red");
                                this.list_model(this.models.employee);
                            });
                        });

                },


                list_admin: function () {
                    $.ajax({
                        url: "route('admin.list')",
                        method: "GET",
                        dataType: "json",
                    }).done(response => {
                        this.resp = response['admin_list'];
                        this.resp = this.resp.concat(response['resp_list']);
                        this.resp = this.resp.concat(response['default']);
                        this.list_group();
                    });
                },
                edit: function () {
                    this.view.form.data = Object.assign({}, this.selected_employee);
                    this.view.form.show = true;
                },
                popup: function (id) {
                    $.ajax({
                        url: "route('emp.edit')",
                        method: "GET",
                        dataType: "json",
                        data: {
                            id: id
                        },
                    }).done(response => {
                        this.form = response;
                        this.gestor_name = response['gestor_name'];
                        this.dialog = true;
                    });
                },
                popup2: function (id) {
                    this.dialog2 = true;
                },
                popup3: function (id) {
                    this.dialog3 = true;
                    this.list_comment(id);
                },



                remove(item) {
                    const index = this.friends.indexOf(item.name)
                    if (index >= 0) this.friends.splice(index, 1)
                },
                searching: function (search) {
                    this.search = search;
                },
            },
            mounted() {
                this.list_model(this.models.employee);
                this.list_model(this.models.profile);
                this.list_model(this.models.template);
            }
        };
    </script>







    @endsection
