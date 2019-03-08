@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Gestores e Responsáveis')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap>
        <v-flex xs6 sm6 class="py-2">
            <v-btn-toggle v-model="view.filter_type" mandatory>
                <v-btn flat>
                    <v-icon>person</v-icon>
                    Todos
                </v-btn>
                <v-btn flat>
                    <v-icon>group</v-icon>
                    Grupos
                </v-btn>
                <v-btn flat>
                    <v-icon>domain</v-icon>
                    Site
                </v-btn>
            </v-btn-toggle>
        </v-flex>
        <v-flex xs6 sm6 class="text-xs-right mb-2">
            <v-btn class="ma-0 " @click="show_user()" color="primary">Adicionar
                Usuário</v-btn>
            <v-btn class="ma-0 " @click="show_form_group();view.filter_type=1" color="primary">Adicionar Grupo</v-btn>
        </v-flex>
        <!--VIEW FILTRO POS-->
        <v-flex xs12 class="pa-0 ma-0" v-if="view.filter_type==0">
            <v-card>
                <v-subheader>Administradores</v-subheader>
                <v-list class='pa-0'>
                    <template v-for='adm in models.user.list' v-if='adm.is_admin == 2'>
                        <v-list-tile avatar @click=''>
                            <v-list-tile-avatar>
                                <v-icon>person</v-icon>
                            </v-list-tile-avatar>
                            <v-list-tile-content @click='show_user(adm,site_name(adm.id))'>
                                <v-list-tile-action>
                                    @{{adm.name}}
                                </v-list-tile-action>
                            </v-list-tile-content>
                            <div style='display:absolute;right:0'>
                                <v-btn class='ma-0' color="primary" flat fab small @click='destroy_user(adm.id)'>
                                    <v-icon> delete_outline</v-icon>
                                </v-btn>
                            </div>
                        </v-list-tile>
                        <v-divider></v-divider>
                    </template>

                </v-list>
                <v-subheader>Gestores</v-subheader>
                <v-list class='pa-0'>
                    <template v-for='adm in models.user.list' v-if='adm.is_admin == 1'>
                        <v-list-tile avatar @click=''>
                            <v-list-tile-avatar>
                                <v-icon>person</v-icon>
                            </v-list-tile-avatar>
                            <v-list-tile-content @click='show_user(adm,site_name(adm.id))'>
                                <v-list-tile-action>
                                    @{{adm.name}}
                                </v-list-tile-action>
                            </v-list-tile-content>
                            <div style='display:absolute;right:0'>
                                <v-btn class='ma-0' color="primary" flat fab small @click='destroy_user(adm.id)'>
                                    <v-icon> delete_outline</v-icon>
                                </v-btn>
                            </div>
                        </v-list-tile>
                        <v-divider></v-divider>
                    </template>

                </v-list>
                <v-subheader>Responsáveis</v-subheader>
                <v-list class='pa-0'>
                    <template v-for='adm in models.user.list' v-if='adm.is_admin == 0'>
                        <v-list-tile avatar @click=''>
                            <v-list-tile-avatar>
                                <v-icon>person</v-icon>
                            </v-list-tile-avatar>
                            <v-list-tile-content @click='show_user(adm,site_name(adm.id))'>
                                <v-list-tile-action>
                                    @{{adm.name}}
                                </v-list-tile-action>
                            </v-list-tile-content>
                            <div style='display:absolute;right:0'>
                                <v-btn class='ma-0' color="primary" flat fab small @click='destroy_user(adm.id)'>
                                    <v-icon> delete_outline</v-icon>
                                </v-btn>
                            </div>
                        </v-list-tile>
                        <v-divider></v-divider>
                    </template>

                </v-list>
            </v-card>
        </v-flex>
        <!--VIEW FILTRO GROUP-->
        <v-flex xs12 class="pa-0 ma-0" v-if="view.filter_type==1">
            <v-card>
                <v-list class='pa-0'>
                    <template v-for='g in models.group.list'>
                        <v-list-tile avatar @click=''>
                            <v-list-tile-avatar>
                                <v-icon>group</v-icon>
                            </v-list-tile-avatar>
                            <v-list-tile-content @click='show_form_group(g)'>
                                <v-list-tile-action>
                                    @{{g.name}}
                                </v-list-tile-action>
                            </v-list-tile-content>
                            <div style='display:absolute;right:0'>
                                <v-btn class='ma-0' color="primary" flat fab small @click='group_destroy(g.id)'>
                                    <v-icon> delete_outline</v-icon>
                                </v-btn>
                            </div>
                        </v-list-tile>
                        <v-divider></v-divider>
                    </template>
                </v-list>
            </v-card>
        </v-flex>


        <!--VIEW FILTRO SITE-->
        <v-flex xs12 class="pa-0 ma-0" v-if="view.filter_type==2">
            <v-autocomplete v-model="view.site.autocomplete" :items="models.site.list" item-text='complete_name'
                item-value='id' solo prepend-inner-icon="domain" placeholder="Digite o  site que deseja ver os administradores"></v-autocomplete>
            <template v-if='view.site.autocomplete != ""'>
                <v-card>
                    <v-list class='pa-0'>
                        <template v-for='adm in user_site(view.site.autocomplete)'>
                            <v-list-tile avatar @click=''>
                                <v-list-tile-avatar>
                                    <v-icon>person</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content @click='show_user(adm,site_name(adm.id))'>
                                    @{{adm.name}}
                                </v-list-tile-content>
                                <div style='display:absolute;right:0'>
                                    <v-btn color="primary" flat fab small @click='destroy_user(adm.id)'>
                                        <v-icon> delete_outline</v-icon>
                                    </v-btn>
                                </div>
                            </v-list-tile>
                            <v-divider></v-divider>
                        </template>
                        <v-list-tile avatar v-if='user_site(view.site.autocomplete).length == 0'>
                            <v-list-tile-content>
                                <v-list-tile-action>
                                    Nenhum gestor ou responsável cadastrado nesse site
                                </v-list-tile-action>
                            </v-list-tile-content>
                        </v-list-tile>


                    </v-list>

                </v-card>
            </template>

        </v-flex>
    </v-layout>
</v-container>

<!--POPUP CREATE GROUP-->
<v-dialog v-model="view.group.form.show" max-width="600" persistent>
    <v-card>
        <v-toolbar color="primary" class='headline' dark>
            @{{view.group.form.is_edit? 'Editar grupo':'Adicionar grupo'}}
        </v-toolbar>
        <v-card-text>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-text-field v-model='view.group.form.data.name' :rules="rules.name" label='Nome do grupo'
                        required></v-text-field>
                    <v-text-field v-model='view.group.form.data.email' :rules="rules.email" label='E-Mail do grupo'
                        required></v-text-field>
                </v-flex>
                <v-flex xs12>
                    <v-list class='pa-0' style='max-height:300px;overflow:auto'>
                        <template v-for='u in view.group.form.users'>
                            <v-list-tile avatar @click=''>
                                <v-list-tile-avatar>
                                    <v-icon>person</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-action>
                                        @{{u.name}}
                                    </v-list-tile-action>
                                </v-list-tile-content>
                                <div style='display:absolute;right:0'>
                                    <v-btn color="primary" flat fab small @click='form_delete_user_group(u)'>
                                        <v-icon> delete_outline</v-icon>
                                    </v-btn>
                                </div>
                            </v-list-tile>
                            <v-divider></v-divider>
                        </template>
                        <v-list-tile avatar v-if='view.group.form.users.length == 0'>
                            <v-list-tile-content>
                                <v-list-tile-action>
                                    Nenhum usuário adicionado a esse grupo
                                </v-list-tile-action>
                            </v-list-tile-content>
                        </v-list-tile>


                    </v-list>
                </v-flex>
                <v-flex xs12>
                    <v-autocomplete v-model='view.group.form.user_selected' :items='user_group(0)' item-text='name'
                        item-value='id' no-data-text='Nenhum usuário disponível para adicionar ao grupo'
                        prepend-inner-icon="add" solo @change='form_add_user_group()'></v-autocomplete>
                </v-flex>
            </v-layout>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn dark color="red" @click="view.group.form.show=false;form_delete_user_group()">
                <v-icon class='mr-2'>close</v-icon>Cancelar
            </v-btn>
            <v-btn dark color="green" @click="group_store()">
                <v-icon class='mr-2'>group_add</v-icon>Salvar
            </v-btn>
        </v-card-actions>
    </v-card>

</v-dialog>
</v-layout>
@endsection

@section('l-js')
<script>
    vue_page = {
        data() {
            return {
                view: {
                    filter_type: 0,
                    site: {
                        autocomplete: ''
                    },
                    group: {
                        form: {
                            user_selected: '',
                            users: [],
                            show: false,
                            data: {}
                        }
                    }
                },
                rules: {
                    name: [
                        v => !!v || 'Campo obrigatório',
                        v => (v && v.length <= 25) || 'Máximo 25 caracteres'
                    ],
                    email: [
                        v => !!v || 'E-mail é obrigatório!',
                        v => /.+@.+/.test(v) || 'E-mail deve ser válido!'
                    ],
                },
                search: '',
            }
        },
        computed: {
            prof_view: function () {
                if ("true" == "{{$prof_view}}") return true;
                return false;
            },
            admin_list_group: function () {

                if (this.model_group == null) return;
                var array = [];
                for (adm of this.admin) {
                    if (adm.group != this.groups[this.model_group].id) {
                        array.push(adm);
                    }
                }
                return array;
            }
        },
        methods: {
            destroy_user: function (id) {
                if (this.user.id == id) {
                    this.notify('Você não pode deletar seu próprio usuário', 'red');
                    return
                }
                this.confirm('Confirmação', 'Deseja mesmo deletar esse usuário?', 'red', () => {
                    this.destroy_model(this.models.user, id, () => {
                        this.notify('Usuário deletado', 'yellow darken-3');
                        this.list_model(this.models.user);
                        this.dialog_user.show = false;
                    });
                });
            },
            searching: function (search) {
                this.search = search;
            },

            show_form_group: function (group) {
                if (typeof group == 'undefined') {
                    group = {
                        name: ''
                    }
                    this.view.group.form.users = [];
                    this.view.group.form.is_edit = false;
                } else {
                    this.view.group.form.users = this.user_group(group.id);
                    this.view.group.form.is_edit = true;
                }
                this.view.group.form.show = true;
                this.view.group.form.data = group;
            },
            form_add_user_group: function () {
                user = this.get_model(this.models.user, this.view.group.form.user_selected);
                this.view.group.form.users.push(user);
                this.view.group.form.user_selected = "";
                user.group = -1;
            },
            form_delete_user_group: function (user) {
                var i = 0;
                if (typeof user == 'undefined') {
                    delete_all = true;
                } else delete_all = false;
                for (u of this.view.group.form.users) {
                    if (delete_all) {
                        if (u.group == -1) u.group = 0
                    } else if (u.id == user.id) {
                        u.group = 0;
                        this.view.group.form.users.splice(i, 1);
                    }
                    i++;
                }
            },
            group_store: function () {
                this.view.group.form.data.team = [];
                for (u of this.view.group.form.users) {
                    this.view.group.form.data.team.push(u.id);
                }
                this.store_model(this.models.group, this.view.group.form.data, () => {
                    this.notify('Grupo salvo!', 'green');
                    this.list_model(this.models.group);
                    this.list_model(this.models.user);
                    this.view.group.form.show = false;
                })
            },
            group_destroy: function (id) {
                this.confirm('Confirmação', 'Deseja mesmo deletar esse grupo?', 'red', () => {
                    this.destroy_model(this.models.group, id, () => {
                        this.notify('Grupo deletado', 'yellow darken-3');
                        this.list_model(this.models.group);
                    });
                });
            }
        },
        mounted() {
            this.prof_view2 = this.prof_view;
        }
    };

</script>
@endsection
