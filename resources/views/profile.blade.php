@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Perfis')
@section('l-css')
<link href="{{asset('plugins/nestable/nestable.css')}}" rel="stylesheet">
@endsection

@section('l-content')

<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap v-if="!form_view">
        <v-flex xs12 class='text-xs-right'>
            <v-form ref='form_profile'>
                <v-transition>
                    <v-text-field v-show='view.new_profile.show' v-model='view.new_profile.name' placeholder="Nome do perfil"
                        solo :rules='view.new_profile.rules'>
                        <template slot='append'>
                            <v-btn color="success" fab small dark @click='store(0)'>
                                <v-icon>check</v-icon>
                            </v-btn>
                            <v-btn color="red" fab small dark @click='view.new_profile.show=false'>
                                <v-icon>close</v-icon>
                            </v-btn>
                        </template>
                    </v-text-field>
                </v-transition>
            </v-form>
            <v-btn v-show='!view.new_profile.show' color="primary" @click="add()">+ Adicionar Perfil</v-btn>
        </v-flex>

        <v-flex>
            <v-card height="100%">
                <v-list row wrap class='pa-0'>
                    <v-subheader>Todos</v-subheader>
                    <v-item-group v-model='view.selected_profile'>
                        <template v-for="(p,i) in models.profile.list">
                            <v-item>
                                <v-list-tile xs12 slot-scope="{ active, toggle }" @click=''>
                                    <v-list-tile-content @click="toggle">
                                        <v-list-title-title :class="active?'red--text':''">
                                            @{{p.name}}
                                        </v-list-title-title>
                                    </v-list-tile-content>
                                    <v-list-tile-action>
                                        <v-btn color="primary" fab flat small @click='remove(p.id)'>
                                            <v-icon>delete_outline</v-icon>
                                        </v-btn>
                                    </v-list-tile-action>
                                </v-list-tile>

                            </v-item>
                            <v-divider></v-divider>
                        </template>
                    </v-item-group>
                    <v-container v-if='models.profile.list.length==0'>Nenhum perfil foi criado</v-container>

                </v-list>

            </v-card>
        </v-flex>

        <v-flex v-if='view.selected_profile > -1' sl8 md8>
            <v-card height="100%">
                <v-toolbar color="primary" class='headline' dark>
                    Informações do perfil
                </v-toolbar>
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-text-field label='Nome do perfil' v-model='selected_profile.name' :rules='view.new_profile.rules'
                                ref='profile_name'></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <p class='grey--text'>Lista de tarefas</p>
                            <v-list style='max-height:200px;overflow:auto'>
                                <template v-for='t in models.template.list'>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title @click='edit_template(t.id)' style='cursor:pointer'>@{{t.name}}</v-list-tile-title>
                                        </v-list-tile-content>
                                        <div style='position:absolute;right:0'>
                                            <v-btn color="primary" class='ma-0' dark fab small flat @click='edit_template(t.id)'>
                                                <v-icon>visibility</v-icon>
                                            </v-btn>
                                            <v-btn color="primary" class='ma-0' dark fab small flat @click='destroy_template(t.id)'>
                                                <v-icon> delete_outline</v-icon>
                                            </v-btn>
                                        </div>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                </template>
                                <template v-if='models.template.list.length == 0'>
                                    Nenhuma lista de tarefas foi criada para esse perfil
                                </template>
                            </v-list>
                        </v-flex>
                        <v-flex xs12 class='text-xs-right'>
                            <v-btn color="green" dark v-if='view.need_save' @click='store(selected_profile.id)'>
                                <v-icon class='mr-2'>save</v-icon>Salvar
                            </v-btn>
                            <v-btn color="info" dark @click='clear_new_template();view.new_template.show=true'>
                                <v-icon class='mr-2'>playlist_add</v-icon>
                                Cria lista de tarefas
                            </v-btn>

                        </v-flex>
                    </v-layout>
                </v-container>

            </v-card>
        </v-flex>

    </v-layout>
    <v-layout row wrap v-if="form_view">
        <v-flex s12>
            <v-card>
                <v-container grid-list-xs>
                    <div class='display-3'>@{{form_texts.title}}</div>
                    <v-form ref='form'>
                        <v-card-text>
                            <v-text-field v-model="form.name" label="Nome" required :rules="rules.name" counter='25'></v-text-field>
                            <v-btn @click="store" color="primary">@{{form_texts.button}}</v-btn>
                        </v-card-text>
                    </v-form>
                </v-container>
            </v-card>
        </v-flex>
    </v-layout>
</v-container>

<v-dialog v-model="view.new_template.show" max-width="800px" scrollable transition="dialog-transition">
    <v-card>
        <v-toolbar color="primary headline" dark class='headline'>
            @{{view.new_template.editing?"Editar lista de tarefas":"Nova lista de tarefas"}}
        </v-toolbar>
        <v-card-text>
            <v-container grid-list-xs>
                <v-layout row wrap>
                    </v-flex>
                    <v-flex xs12>
                        <v-text-field label='Nome da lista de tarefas' v-model='view.new_template.name' :rules='view.new_template.rules'
                            ref='template_name'></v-text-field>
                    </v-flex>
                    <template v-if='view.new_template.tasks.length>0'>
                        <v-flex xs12>
                            <p class='headline'>Tarefas</p>
                        </v-flex>
                        <v-flex xs12>
                            <Tree :data="view.new_template.tasks" draggable="draggable" ref='tree'>
                                <div slot-scope="{data, store}" class='text-truncate'>
                                    <table v-if='!data.isDragPlaceHolder'>
                                        <tr>
                                            <td>
                                                <v-icon v-if="data.children && data.children.length" @click="store.toggleOpen(data)">@{{data.open
                                                    ? 'expand_more' : 'expand_less'}}</v-icon>
                                                <v-icon v-else color='grey'>drag_indicator</v-icon>
                                            </td>
                                            <td @click="store.toggleOpen(data)">@{{data.text}}</td>
                                            <td>
                                                <v-icon color='red' @click='store.deleteNode(data)' class='ml-2'>delete_outline</v-icon>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </Tree>
                        </v-flex>
                        <v-flex xs12 class='text-xs-center'>
                            <v-chip>Arraste as tarefas dentro de outras para criar dependências</v-chip>
                        </v-flex>
                    </template>
                    <v-flex xs12>
                        <v-autocomplete no-data-text='Nenhuma tarefa encontrada' v-model="view.new_template.task_selected"
                            :items="models.task.list" :item-text="'name'" :item-value="'id'" color='primary'
                            prepend-icon="add_box" placeholder="Adicionar tarefa" @change='add_task()'>
                        </v-autocomplete>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card-text>

        <v-divider></v-divider>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red" outline dark @click='clear_new_template()'>
                <v-icon class='mr-2'>close</v-icon>Cancelar
            </v-btn>
            <v-btn color="green" outline dark @click='add_template()'>
                <v-icon class='mr-2'>save</v-icon>Confimar
            </v-btn>
        </v-card-actions>
    </v-card>
</v-dialog>


@endsection

@section('l-js')
<script src='{{asset("sources/profiles.js")}}'></script>
<script src='{{asset("sources/checklists_template.js")}}'></script>
<script src='{{asset("sources/tasks.js")}}'></script>
<script src='{{asset("plugins/nestable/nestable.js")}}'></script>

<script>
    vue_page = {
        mixins: [sources_profiles, sources_checklists_template, sources_tasks],
        components: {
            Tree: vueDraggableNestedTree.DraggableTree
        },
        data() {
            return {
                form_view: false,
                form_texts: {
                    title: "",
                    button: ""
                },
                form: {
                    id: "",
                    name: '',
                    checklists: ''
                },
                view: {
                    selected_profile: -1,
                    new_profile: {
                        show: false,
                        name: '',
                        rules: [
                            v => !!v || 'Campo obrigtório',
                        ]
                    },
                    need_save: false,
                    new_template: {
                        editing: false,
                        show: false,
                        name: '',
                        task_selected: '',
                        tasks: [],
                        rules: [
                            v => !!v || 'Campo obrigtório',
                        ]
                    }
                },
            }
        },
        computed: {
            search_data: function () {
                var array = [];
                for (p of this.profiles) {
                    array.push(this.search_text(this.search, p.name));
                }
                return array;
            },
            selected_profile: function () {
                Vue.nextTick(() => {
                    this.view.need_save = false
                    this.view.new_template.show = false;
                    this.clear_new_template();
                });
                if (this.view.selected_profile > -1) {
                    this.list_model(this.models.template, {
                        id: this.models.profile.list[this.view.selected_profile].id
                    });
                }
                return this.models.profile.list[this.view.selected_profile];
            }

        },
        watch: {
            "view.selected_profile": function () {
                if (this.view.need_save) {
                    this.list_model(this.models.profile);
                }
            },
            "selected_profile.name": function () {
                this.view.need_save = true;
            },
        },
        methods: {
            add: function () {
                this.view.new_profile.show = true;
                Vue.nextTick(() => {
                    this.$refs["profile_name"].focus();
                });

            },
            store: function (id) {
                if (id == 0) {
                    if (this.$refs.form_profile.validate()) {
                        profile = {
                            name: this.view.new_profile.name
                        };
                        this.view.new_profile.name = '';
                        this.view.new_profile.show = false;
                        this.store_model(this.models.profile, profile, (r) => {
                            this.list_model(this.models.profile);
                            this.notify("Perfil criado com sucesso!", "success");
                        });
                    }
                } else {
                    if (this.$refs.profile_name.validate()) {
                        this.view.need_save = false;
                        this.store_model(this.models.profile, this.selected_profile, () => {

                            this.notify("Perfil salvo", "success");

                        });
                    }
                }
            },
            remove: function (id) {
                this.confirm("Profile", "Deseja deletar esse perfil?", "red", () => {
                    if (this.models.profile.list.length == 1) this.view.selected_profile = -1;
                    this.destroy_model(this.models.profile, id, () => {
                        this.list_model(this.models.profile);
                    })
                })
            },
            searching: function (search) {
                this.search = search;
            },
            add_task: function () {
                task = this.get_model(this.models.task, this.view.new_template.task_selected);
                if (this.check_task(task.id)) {
                    this.view.new_template.tasks.push({
                        text: task.name,
                        task_id: task.id,
                        children: []
                    })
                    this.view.new_template.task_selected = -1;
                } else this.notify('A tarefa já foi adicionada na lista', 'red', 2000);

            },
            check_task: function (id, data) {
                if (typeof data == 'undefined') {
                    if (this.view.new_template.tasks.length > 0) data = this.$refs.tree.getPureData();
                    else return true;

                }
                for (n of data) {
                    if (n.task_id == id) return false;
                    if (typeof n.children != 'undefined') {
                        if (!this.check_task(id, n.children)) return false;
                    }
                }
                return true;

            },
            add_template: function () {
                if (this.$refs.template_name.validate()) {
                    data = {
                        name: this.view.new_template.name,
                        tasks: this.$refs.tree.getPureData(),
                        profile_id: this.selected_profile.id
                    }
                    if (this.view.new_template.editing) data.id = this.view.new_template.id;
                    this.store_model(this.models.template, data, () => {
                        this.view.new_template.show = false;
                        this.clear_new_template();
                        this.notify("Lista de tarefas salva!","green");
                        this.list_model(this.models.template, {
                            id: this.selected_profile.id
                        });

                    })
                }
            },
            destroy_template: function (id) {
                this.confirm("Exclusão", 'Deseja mesmo excluir essa lista de tarefa?', 'grey', () => {
                    this.destroy_model(this.models.template, id, () => {
                        this.list_model(this.models.template, {
                            id: this.selected_profile.id
                        });
                    });
                });

            },
            edit_template: function (id) {
                this.view.new_template.editing = true;
                this.view.new_template.show = true;
                this.view.new_template.id = id;
                this.template_tree(id, (r) => {
                    this.view.new_template.tasks = r;
                })
                this.view.new_template.name = this.get_model(this.models.template, id).name

            },
            clear_new_template: function () {
                this.view.new_template.editing = false;
                this.view.new_template.show = false;
                this.view.new_template.task_selected = '';
                this.view.new_template.name = '';
                this.view.new_template.id = '';
                this.view.new_template.tasks = []
            }
        },
        mounted() {
            this.list_model(this.models.profile);
            this.list_model(this.models.task);
        }
    };
</script>
@endsection
