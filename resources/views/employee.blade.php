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
                    <v-container v-if='models.employee.list.length==0'>Nenhum empregado criado</v-container>

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
                            <v-list style='max-height:200px;overflow:auto'>
                                <template v-for='t in models.checklist.list'>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title @click='view_checklist(t.id,t.checklist_template_id)' style='cursor:pointer'>@{{get_model(models.template,t.checklist_template_id).name}}</v-list-tile-title>
                                        </v-list-tile-content>
                                        <div style='position:absolute;right:0'>
                                            <v-btn color="primary" class='ma-0' dark fab small flat @click='destroy_checklist(t.id)'>
                                                <v-icon> delete_outline</v-icon>
                                            </v-btn>
                                        </div>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                </template>
                                <template v-if='models.checklist.list.length == 0'>
                                    Nenhuma lista de tarefas foi adicionada para esse usuário
                                </template>
                            </v-list>
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
                <v-btn color="green" outline dark @click='add_checklist()'>
                    <v-icon class='mr-2'>save</v-icon>Adicionar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog v-model="view.checklist.show" scrollable max-width="500px" transition="dialog-transition" v-if='view.selected_employee !=-1'>
        <v-card>
            <v-toolbar color="primary headline" dark class='headline'>
                @{{view.checklist.data.name}}
            </v-toolbar>
            <v-card-text>
                <v-container grid-list-xs>
                    <v-layout row wrap>
                        </v-flex>
                        <template v-if='view.checklist.data.tasks.length>0'>
                            <v-flex xs12>
                                <p class='headline'>Tarefas</p>
                            </v-flex>
                            <v-flex xs12>
                                <Tree :data="view.checklist.data.tasks" ref='tree'>
                                    <div slot-scope="{data, store}" class='text-truncate'>
                                        <table v-if='!data.isDragPlaceHolder'>
                                            <tr>
                                                <td>
                                                    <v-icon v-if="data.children && data.children.length" @click="store.toggleOpen(data)">@{{data.open
                                                        ? 'expand_more' : 'expand_less'}}</v-icon>
                                                </td>
                                            <td v-if='check_get(data.task_id).status==1' ><v-icon color='green'>check</v-icon></td>
                                            <td v-if='check_get(data.task_id).status==0' ><v-icon color='primary'>priority_high</v-icon></td>
                                            <td @click="store.toggleOpen(data)" >@{{data.text}} </td>
                                            <td class='grey--text'>(@{{check_get_status(data.task_id)}})</td>

                                            </tr>
                                        </table>
                                        <v-divider></v-divider>
                                    </div>
                                </Tree>
                            </v-flex>
                        </template>
                    </v-layout>
                </v-container>
            </v-card-text>

            <v-divider></v-divider>
            <v-card-actions>
            </v-card-actions>
        </v-card>
    </v-dialog>

    @endsection

    @section('l-js')
    <script src='{{asset("sources/profiles.js")}}'></script>
    <script src='{{asset("sources/checklists_template.js")}}'></script>
    <script src='{{asset("sources/checklists.js")}}'></script>
    <script src='{{asset("sources/checks.js")}}'></script>
    <script src='{{asset("sources/tasks.js")}}'></script>
    <script src='{{asset("sources/employees.js")}}'></script>
    <script src='{{asset("plugins/nestable/nestable.js")}}'></script>

    <script>
        vue_page = {
            mixins: [sources_profiles, sources_checklists_template, sources_tasks, sources_employees,
                sources_checklists,sources_checks
            ],
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
                        checklist: {
                            show: false,
                            data: {
                                name: "",
                                tasks:[],
                            }
                        },
                        form: {
                            show: false,
                            data: {
                            },
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
                    if (this.view.selected_employee != -1) this.list_model(this.models.checklist, {
                        id: this.models.employee.list[this.view.selected_employee].id
                    });
                    return this.models.employee.list[this.view.selected_employee];
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
                edit: function () {
                    this.view.form.data = Object.assign({}, this.selected_employee);
                    this.view.form.show = true;
                },
                destroy: function (id) {
                    this.confirm("Confirmação",
                        "Deseja mesmo deletar esse empregado? Todas sas informações e listas de tarefas serão removidas",
                        "red", () => {
                            this.destroy_model(this.models.employee, id, () => {
                                this.notify("Empregado deletado", "red");
                                this.list_model(this.models.employee);
                            });
                        });

                },
                add_checklist: function () {
                    this.store_model(this.models.checklist, {
                        employee_id: this.selected_employee.id,
                        template_id: this.view.new_checklist.id
                    }, () => {
                        this.notify("Lista de tarefas adicionada", "green");
                        this.list_model(this.models.checklist, {
                            id: this.selected_employee.id
                        });
                        this.view.new_checklist.show = false;
                    });
                },
                destroy_checklist: function (id) {
                    this.confirm("Confirmação",
                        "Deseja mesmo deletar essa lista de tarefas?",
                        "red", () => {
                            this.destroy_model(this.models.checklist, id, () => {
                                this.notify("lista de tarefaa deletada", "red");
                                this.list_model(this.models.checklist, {
                                    id: this.selected_employee.id
                                });
                            });
                        });

                },
                view_checklist: function (id,template_id) {
                    this.view.checklist.show = true;
                    this.list_model(this.models.check,{checklist_id:id});
                    this.view.checklist.data.name = this.get_model(this.models.template,template_id).name;
                    this.template_tree(template_id, (r) => {
                        this.view.checklist.data.tasks = r;
                    })
                }
            },
            mounted() {
                this.list_model(this.models.employee);
                this.list_model(this.models.profile);
                this.list_model(this.models.template);
            }
        };
    </script>







    @endsection
