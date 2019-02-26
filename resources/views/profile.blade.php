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
                <v-toolbar color="primary" dark class='headline'>
                    Lista
                </v-toolbar>
                <v-list row wrap>
                    <v-item-group v-model='view.selected_profile'>
                        <v-item v-for="p in models.profile.list">
                            <v-list-tile xs12 slot-scope="{ active, toggle }" @click="toggle">
                                <v-list-title-title :class="active?'red--text':''">
                                    @{{p.name}}
                                </v-list-title-title>
                            </v-list-tile>
                        </v-item>
                    </v-item-group>
                </v-list>

            </v-card>
        </v-flex>

        <v-flex v-if='view.selected_profile > -1' xs8>
            <v-card height="100%">
                <v-toolbar color="primary" class='headline' dark>
                    @{{selected_profile.name}}
                </v-toolbar>
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-text-field label='Nome do perfil' v-model='selected_profile.name' :rules='view.new_profile.rules'
                                ref='profile_name'></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <p class='grey--text'>Lista de tarefas</p>
                            <v-divider></v-divider>
                            <v-list>
                                <v-list-tile avatar v-for='t in models.template.list'>
                                    <v-list-tile-content>
                                        @{{t.name}}
                                    </v-list-tile-content>
                                </v-list-tile>
                            </v-list>
                        </v-flex>
                        <v-flex xs12 v-if='view.new_template.show'>
                            <v-text-field label='Nome da lista de tarefas' v-model='view.new_template.name' :rules='view.new_template.rules'
                                ref='template_name'></v-text-field>
                            <Tree :data="view.new_template.tasks" draggable="draggable" ref='tree'>
                                <div slot-scope="{data, store}" @click="store.toggleOpen(data)">
                                    <b v-if="data.children && data.children.length">@{{data.open ? '-' : '+'}}&nbsp;</b><span>@{{data.text}}</span>
                                </div>
                            </Tree>
                            <v-autocomplete no-data-text='Nenhuma tarefa encontrada' v-model="view.new_template.task_selected"
                                :items="models.task.list" :item-text="'name'" :item-value="'id'" color='primary'
                                prepend-icon="list" placeholder="Adicionar tarefa" @change='add_task()'>
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs12 class='text-xs-right'>
                            <v-btn color="red" dark @click='remove'>
                                <v-icon class='mr-2'>delete_forever</v-icon> Excluir
                            </v-btn>
                            <v-btn color="green" dark v-if='view.need_save' @click='store(selected_profile.id)'>
                                <v-icon class='mr-2'>save</v-icon>Salvar
                            </v-btn>
                            <v-btn v-if='!view.new_template.show' color="info" dark @click='view.new_template.show=true'>+
                                Cria lista de tarefas</v-btn>
                            <v-btn v-else color="green" dark @click='add_template()'>
                                <v-icon class='mr-2'>save</v-icon>Salvar lista de tarefas
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
                });
                this.list_model(this.models.template, {
                    id: this.models.profile.list[this.view.selected_profile].id
                });
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
            remove: function () {
                this.confirm("Profile", "Deseja deletar esse perfil?", "red", () => {
                    this.destroy_model(this.models.profile, this.selected_profile.id, () => {
                        this.list_model(this.models.profile);
                        this.notify("Perfil removido", "error");
                    })
                })
            },
            searching: function (search) {
                this.search = search;
            },
            add_task: function () {
                task = this.get_model(this.models.task, this.view.new_template.task_selected);
                this.view.new_template.tasks.push({
                    text: task.name,
                    task_id: task.id,
                    children: []
                })
                this.view.new_template.task_selected = -1;
            },
            add_template: function () {
                if (this.$refs.template_name.validate()) {
                    this.store_model(this.models.template, {
                        name: this.view.new_template.name,
                        tasks: this.$refs.tree.getPureData(),
                        profile_id: this.selected_profile.id
                    }, () => {
                        this.view.new_template.show = false;
                    })
                }
            }
        },
        mounted() {
            this.list_model(this.models.profile);
            this.list_model(this.models.task);
            this.setMenu('profile');
        }
    };
</script>
@endsection
