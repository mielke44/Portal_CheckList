@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Admins')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap v-if="!form_view && !prof_view">
        <v-flex xs6 sm6 class="py-2">
            <v-btn-toggle v-model="Filter.model" mandatory>
                <v-btn flat>
                    <v-icon>group</v-icon>
                    Grupos
                </v-btn>
                <v-btn flat>
                    <v-icon>person</v-icon>
                    Todos
                </v-btn>
                <v-btn flat>
                    <v-icon>domain</v-icon>
                    Site
                </v-btn>
            </v-btn-toggle>
        </v-flex>
        <v-flex xs6 sm6 class="text-xs-right">
            <v-btn class="ma-0 " v-if="user.is_admin==1" @click="is_admin=true;add();" color="primary">Adicionar Usuário</v-btn>
            <v-btn class="ma-0 " v-if="Filter.model==0" @click="addGroup();" color="primary">Adicionar Grupo</v-btn>
        </v-flex>
        <!--VIEW FILTRO GROUP-->
        <v-flex xs12 class="pa-0 ma-0" v-if="Filter.model==0">
            <v-expansion-panel  v-model="model_group">
                <v-expansion-panel-content color="primary" v-for='grp in groups'>
                    <div slot="header">
                        <v-layout row wrap fill-height align-center>
                            <v-flex  class="font-weight-bold" xs9>
                                @{{grp.name}}
                            </v-flex>
                        </v-layout>
                    </div>
                    <v-flex  xs12 class="text-xs-right font-weight-bold">
                            <v-icon @click="editGroup(grp)">edit</v-icon>
                            <v-icon @click="destroy_group(grp.id)">delete</v-icon>
                            <v-container>
                                <v-autocomplete v-model="form.team" :items="admin_list_group" color="black" hide-no-data hide-selected multiple
                                    item-text="name" item-value="id" label="Adicionar ao grupo" append-outer-icon="add" @click:append-outer="ChangeTeam(form.team, grp.id,2)"></v-autocomplete>
                            </v-container>
                    </v-flex>
                    <v-container>
                        <v-expansion-panel>
                                <v-expansion-panel-content v-if="adm.group==grp.id" v-for='adm in admin'>
                                    <div slot="header">
                                        <v-layout row wrap fill-height align-center>
                                            <v-flex xs11>
                                                @{{adm.name}}
                                            </v-flex>
                                            <v-icon @click="ChangeTeam(adm.id, grp.id,1)">close</v-icon>
                                        </v-layout>
                                        
                                    </div>
                                    <v-layout class="pa-2" row wrap>
                                            <v-flex xs6>E-mail:</v-flex>
                                            <v-flex xs6 class='font-weight-bold'>@{{adm.email}}</v-flex>
                                            <v-flex xs6>Data admissão</v-flex>
                                            <v-flex xs6 class='font-weight-bold'>@{{adm.created_at}}</v-flex>
                                            <v-flex xs6>Site</v-flex>
                                            <v-flex xs6 class='font-weight-bold'>@{{getSiteName(adm.site)}}</v-flex>
                                            <v-flex xs12 v-if="user.is_admin==1" class='text-xs-right'>
                                                <v-btn @click="edit(adm.id)" color="yellow darken-2" outline>
                                                    <v-icon dark class='mr-2'>edit</v-icon> Editar
                                                </v-btn>
                                                <v-btn @click="destroy(adm.id)" color="red" outline>
                                                    <v-icon dark class='mr-2'>delete</v-icon> Remover
                                                </v-btn>
                                            </v-flex>
                                    </v-layout>
                                </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-container>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-flex>
        <!--VIEW FILTRO POS-->
        <v-flex xs12 class="pa-0 ma-0" v-if="Filter.model==1">
            <v-flex xs12>
                <v-expansion-panel>
                    <v-expansion-panel-content v-for='adm in admin'>
                        <div slot="header">
                            <v-layout row wrap fill-height align-center>
                                <v-flex xs6>
                                    @{{adm.name}}
                                </v-flex>
                                <!--<v-flex xs3 class='text-xs-right'>
                                    <span class='mr-2'>@{{adm.checks}}/@{{adm.list}}</span>
                                    <v-progress-circular rotate="-90" :value="adm.checks/adm.list*100" color="primary" class='mr-2'
                                        width='7'></v-progress-circular>
                                </v-flex>-->
                            </v-layout>
                        </div>
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-flex xs3>E-mail:</v-flex>
                                <v-flex xs3 class='font-weight-bold'>@{{adm.email}}</v-flex>
                                <v-flex xs3>Data admissão</v-flex>
                                <v-flex xs3 class='font-weight-bold'>@{{adm.created_at}}</v-flex>
                                <v-flex xs3>Site</v-flex>
                                <v-flex xs3 class='font-weight-bold'>@{{getSiteName(adm.site)}}</v-flex>
                                <v-flex xs12 v-if="user.is_admin==1" class='text-xs-right'>
                                    <v-btn @click="edit(adm.id)" color="yellow darken-2" outline>
                                        <v-icon dark class='mr-2'>edit</v-icon> Editar
                                    </v-btn>
                                    <v-btn @click="destroy(adm.id)" color="red" outline>
                                        <v-icon dark class='mr-2'>delete</v-icon> Remover
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-flex>
            <v-flex xs12 sm12 class='text-xs-right'>
                <v-btn class=" ma-0" v-if="user.is_admin==1 && Filter==1" @click="is_admin=false;add();" color="primary">Adicionar Responsável</v-btn>
            </v-flex>
            <v-flex xs12>
                <v-expansion-panel>
                    <v-expansion-panel-content v-for='r in resp'>
                        <div slot="header">
                            <v-layout row wrap fill-height align-center>
                                <v-flex xs6>
                                    @{{r.name}}
                                </v-flex>
                                <!--<v-flex xs3 class='text-xs-right'>
                                        <span class='mr-2'>@{{adm.checks}}/@{{adm.list}}</span>
                                        <v-progress-circular rotate="-90" :value="adm.checks/adm.list*100" color="primary" class='mr-2'
                                            width='7'></v-progress-circular>
                                    </v-flex>-->
                            </v-layout>
                        </div>
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-flex xs3>E-mail:</v-flex>
                                <v-flex xs3 class='font-weight-bold'>@{{r.email}}</v-flex>
                                <v-flex xs3>Data admissão</v-flex>
                                <v-flex xs3 class='font-weight-bold'>@{{r.created_at}}</v-flex>
                                <v-flex xs3>Site</v-flex>
                                <v-flex xs3 class='font-weight-bold'>@{{getSiteName(r.site)}}</v-flex>
                                <v-flex xs12 v-if="user.is_admin==1" class='text-xs-right'>
                                    <v-btn @click="edit(r.id)" color="yellow darken-2" outline>
                                        <v-icon dark class='mr-2'>edit</v-icon> Editar
                                    </v-btn>
                                    <v-btn @click="destroy(r.id)" color="red" outline>
                                        <v-icon dark class='mr-2'>delete</v-icon> Remover
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-flex>
        </v-flex>
        <!--VIEW FILTRO SITE-->
        <v-flex xs12 class="pa-0 ma-0" v-if="Filter.model==2">
                <v-expansion-panel v-model="model_site">
                    <v-expansion-panel-content v-for='st in sites'>
                        <div slot="header">
                            <v-layout row wrap fill-height align-center>
                                <v-flex class="font-weight-bold" xs6>
                                    @{{getSiteName(st.id)}}
                                </v-flex>
                            </v-layout>
                        </div>
                        <v-container>
                            <v-expansion-panel>
                                    <v-expansion-panel-content v-if="adm.site==st.id" v-for='adm in admin'>
                                        <div slot="header">
                                            <v-layout row wrap fill-height align-center>
                                                <v-flex xs6>
                                                    @{{adm.name}}
                                                </v-flex>
                                            </v-layout>
                                        </div>
                                        <v-layout class="pa-2" row wrap>
                                                <v-flex xs6>E-mail:</v-flex>
                                                <v-flex xs6 class='font-weight-bold'>@{{adm.email}}</v-flex>
                                                <v-flex xs6>Data admissão</v-flex>
                                                <v-flex xs6 class='font-weight-bold'>@{{adm.created_at}}</v-flex>
                                                <v-flex xs6>Site</v-flex>
                                                <v-flex xs6 class='font-weight-bold'>@{{getSiteName(adm.site)}}</v-flex>
                                                <v-flex xs12 v-if="user.is_admin==1" class='text-xs-right'>
                                                    <v-btn @click="edit(adm.id)" color="yellow darken-2" outline>
                                                        <v-icon dark class='mr-2'>edit</v-icon> Editar
                                                    </v-btn>
                                                    <v-btn @click="destroy(adm.id)" color="red" outline>
                                                        <v-icon dark class='mr-2'>delete</v-icon> Remover
                                                    </v-btn>
                                                </v-flex>
                                        </v-layout>
                                    </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-container>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-flex>
        <!--POPUP CREATE GROUP-->
        <v-dialog v-model="popup_group" max-width="600" r>
            <v-card>
                <v-card-title>@{{form_texts.title}}</v-card-title>
                <v-card-text>
                    <v-layout row wrap>
                    <v-flex xs12>
                        <v-text-field v-model='form.name' :rules="rules.name" label='Nome do grupo' required></v-text-field>
                    </v-flex>
                    </v-layout>
                </v-card-content>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="storeGroup()">
                        <v-icon dark class='mr-2'>group_add</v-icon>Salvar
                    </v-btn>
                </v-card-actions>
            </v-card>
            
        </v-dialog>
    </v-layout>



    <!-- PERFIl -->
    <v-layout row wrap v-if="prof_view2">
        <v-flex xs12 sm6 offset-sm3>
            <v-card>
                <v-container grid-list-xs>
                    <div class='display-2'>Perfil</div>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-flex xs3>Nome:</v-flex>
                            <v-flex xs9 class='font-weight-bold'>@{{user.name}}</v-flex>
                            <v-flex xs3>E-mail:</v-flex>
                            <v-flex xs9 class='font-weight-bold'>@{{user.email}}</v-flex>
                            <v-flex xs3>Data admissão:</v-flex>
                            <v-flex xs9 class='font-weight-bold'>@{{user.created_at}}</v-flex>
                            <v-flex xs3>Site</v-flex>
                            <v-flex xs9 class='font-weight-bold'>@{{getSiteName(user.site)}}</v-flex>
                            <v-flex class='text-xs-center'>
                                <v-btn @click="edit(user.id)" color="yellow darken-2" outline>
                                    <v-icon dark class='mr-2'>edit</v-icon> Editar
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-container>
            </v-card>
        </v-flex>
    </v-layout>



    <!-- FORM VIEW-->
    <v-layout row wrap v-if="form_view && !prof_view2">
        <v-flex xs12 sm6 offset-sm3>
            <v-card>
                <v-container grid-list-xs>
                    <div class='display-2'>@{{form_texts.title}}</div>
                    <v-form ref='form'>
                        <v-card-text>
                            <v-text-field v-model="form.name" :rules="rules.name" label="Nome" required></v-text-field>
                            <v-text-field v-model="form.email" :rules="rules.email" label="E-mail" required></v-text-field>
                            <v-checkbox label="Usuário é gestor?" v-model="form.is_admin"></v-checkbox>
                            <v-select v-model="form.site" :items="sites" item-text="complete_name" item-value="id"
                                label="Site" persistent-hint required></v-select>
                            <v-text-field v-if="!this.form_edit" v-model="form.password" :append-icon="show1 ? 'visibility_off' : 'visibility'"
                                :rules="rules.password" :type="show1 ? 'text' : 'password'" name="input-10-1" label="Senha"
                                hint="Senha deve ter 6 caracteres" counter @click:append="show1 = !show1"></v-text-field>
                            <v-text-field v-if="!this.form_edit" v-model="form.passwordc" :rules="rules.passwordc" :type="show1 ? 'text' : 'password'"
                                name="input-10-1" label="Confirmar Senha"></v-text-field>
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
                form_edit:false,
                model_group: 0,
                model_site: 0,
                name: '',
                popup_group: false,
                show1: false,
                admin: [],
                resp: [],
                user: [],
                groups: [],
                form_view: false,
                form_texts: {
                    title: "",
                    button: ""
                },
                Filter: {
                    model:true,
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
                    password: [
                        v => {
                            if ((v && v.length >= 6) || v == "" || v == null) return true;
                            else return 'Mínimo 6 caracteres';
                        }
                    ],
                    passwordc: [
                        v => {
                            if ((this.form.password != '' && v != '') || this.form.password == null) return true;
                            else return 'Campo obrigatório!';
                        },
                        v => {
                            if (v == this.form.password || this.form.password == null) return true;
                            else return 'Senhas não estão iguais!';
                        },
                    ],
                    site: [
                        v => !!v || 'Campo obrigatório!',
                    ],
                },
                form: {
                    id: "",
                    team: '',
                    name: '',
                    email: '',
                    password: '',
                    passwordc: '',
                    site: '',
                    group: '',
                    is_admin: '',
                },
                items: [],
                sites: [],
                prof_view2: [],
                search: '',
            }
        },
        computed: {
            prof_view: function () {
                if ("true" == "{{$prof_view}}") return true;
                return false;
            },
            admin_list_group:function (){
                if(this.model_group==null) return;
                var array=[];
                for(adm of this.admin){
                    if(adm.group!=this.groups[this.model_group].id){
                        array.push(adm);
                    }
                }
            return array;
            }
        },
        methods: {
            add: function () {
                this.form_view = true;
                this.form_texts.title = "Adicionar Usuário";
                this.form_texts.button = "Salvar";
                this.edit = false;
                this.form = {
                    id: "",
                    name: '',
                    type: '',
                    is_admin:'',
                    password:'',
                }
            },
            store: function () {
                if (this.$refs.form.validate()) {
                    this.form.group='';
                    app.confirm("Adicionando/Alterando Registro!", "Confirmar ação de Registro?", "green", () => {
                        $.ajax({
                            url: "{{route('admin.store')}}",
                            method: "POST",
                            dataType: "json",
                            headers: app.headers,
                            data: {
                                form: this.form,
                            },
                            success: (response) => {
                                this.list();
                                this.form_view = false;
                                if (this.form.id == "") app.notify(
                                    "Registro adicionado com sucesso!",
                                    "success");
                                else app.notify("Edição salva", "success");
                            }
                        });
                    })
                }
            },
            addGroup: function () {
                this.popup_group = true;
                this.form = {
                    id: "",
                    name: '',
                    team:"",
                }
                this.form_texts.title = 'Adicionar Grupo'
            },
            editGroup: function(grp){
                this.popup_group = true;
                this.form = {
                    id: grp.id,
                    name: grp.name,
                    team:'',
                }
                this.form_texts.title = 'Editar Grupo'
            },
            storeGroup: function () {
                    app.confirm("Adicionando/Alterando Registro!", "Confirmar ação de Registro?", "green", () => {
                        $.ajax({
                            url: "{{route('group.store')}}",
                            method: "POST",
                            dataType: "json",
                            headers: app.headers,
                            data: {
                                form: this.form,
                            },
                            success: (response) => {
                                this.list_group();
                                this.popup_group = false;
                                if (this.form.id == "") app.notify(
                                    "Registro adicionado com sucesso!",
                                    "success");
                                else app.notify("Edição salva", "success");
                            }
                        });
                    })
            },
            list: function () {
                $.ajax({
                    url: "{{route('admin.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.admin = response['admin_list'];
                    this.user = response['user'];
                    this.resp = response['resp_list']
                });
            },
            list_group: function(){
                $.ajax({
                    url: "{{route('group.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.groups = response;
                });
            },
            getSiteName: function (id) {
                for (i = 0; i < this.sites.length; i++) {
                    if (this.sites[i].id == id) return this.sites[i].complete_name;
                }
            },
            edit: function (id) {
                    $.ajax({
                        url: "{{route('admin.edit')}}",
                        method: "GET",
                        dataType: "json",
                        data: {
                            id: id
                        },
                    }).done(response => {
                        this.form_texts.title = "Editar Admin";
                        this.form_texts.button = "Salvar";
                        this.form = response;
                        this.form.password='';
                        this.form_view = true;
                        this.form_edit=true;
                        this.prof_view2 = false;
                        this.form.site = parseInt(this.form.site);
                    });
            },
            destroy: function (id) {
                app.confirm("Remover registro?",
                    "Todas as informações desse registro serão deletadas.", "red", () => {
                        $.ajax({
                            url: "{{route('admin.remove')}}",
                            method: "DELETE",
                            dataType: "json",
                            headers: app.headers,
                            data: {
                                id: id
                            },
                            success: (response) => {
                                this.list();
                                app.notify("Admin removido", "error");
                            }
                        });
                    })
            },
            destroy_group: function (id) {
                app.confirm("Remover grupo",
                    "Todas as informações desse grupo serão deletadas.", "red", () => {
                        $.ajax({
                            url: "{{route('group.delete')}}",
                            method: "DELETE",
                            dataType: "json",
                            headers: app.headers,
                            data: {
                                id: id
                            },
                            success: (response) => {
                                this.list_group();
                                app.notify("Grupo removido", "success");
                            }
                        });
                    })
            },
            ChangeTeam: function(admin_id,group_id,s){
                //alert(JSON.stringify(this.model_group));
                if(s==1){app.confirm("Remover integrante?",
                    "Este integrante será removido do grupo.", "red", () => {
                        this.form = {
                            id: admin_id,
                            group: group_id,
                            s: s,
                        }
                        $.ajax({
                            url: "{{route('admin.store')}}",
                            method: "POST",
                            dataType: "json",
                            headers: app.headers,
                            data: {
                                form: this.form,
                            },
                            success: (response) => {
                                this.list_group();
                                this.list();
                                app.notify("Integrante Removido!", "success");
                            }
                        });
                })
                }if(s==2){
                    app.confirm("Adicionar integrante?",
                    "Este integrante será adicionado ao grupo.", "yellow darken-2", () => {
                        this.form = {
                            id: admin_id,
                            group: group_id,
                            s: s,
                        }
                        $.ajax({
                            url: "{{route('admin.store')}}",
                            method: "POST",
                            dataType: "json",
                            headers: app.headers,
                            data: {
                                form: this.form,
                            },
                            success: (response) => {
                                this.list_group();
                                this.list();
                                app.notify("Integrante Adicionado!", "success");
                            }
                        });
                })

            }
        },
        mounted: function(){
            app.setMenu('admin');
        },
        searching: function (search) {
            this.search = search;
        },
    },
    mounted() {
            this.list_group();
            this.list();
            this.prof_view2 = this.prof_view;

            $.ajax({
                url: "{{route('site.list')}}",
                method: "GET",
                dataType: "json",
            }).done(response => {
                this.sites = response;
            });
        }
});
</script>
@endsection
