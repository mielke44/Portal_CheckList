@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Empregados')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap v-if="!form_view">
        <v-flex xs3 class='text-xs-left'>
            <v-select solo append-icon="filter_list" v-model="filtro.type" :items="filtros" item-text="name" item-value="name"
                label="Filtros" persistent-hint :rules='rules.profile' ></v-select>
        </v-flex>
        <v-flex>
            <v-select v-model="filtro.site" :items="sites" item-text="complete_name" item-value="id" label="Site" solo
                v-if='filtro.type=="Site"' ></v-select>
                <v-select v-model="filtro.profile" :items="profiles" item-text="name" item-value="id" label="Perfil" solo
                v-if='filtro.type=="Perfil"' ></v-select>
        </v-flex>
        <v-flex class='text-xs-right'>
            <v-btn @click="add()" color="primary">Adicionar empregado</v-btn>
        </v-flex>
        <v-flex xs12>
            <v-expansion-panel v-model='model_employee'>
                <template v-for='(em,i) in employees' >
                <v-expansion-panel-content v-show='search_data[i]'>
                    <div slot="header">
                        <v-layout row wrap fill-height align-center>
                            <v-flex xs6>
                                @{{em.name}}
                            </v-flex>
                            <v-flex xs3>
                                <p>@{{em.profile}}</p>
                                <p class='caption'>@{{siteName(em.site)}}</p>
                            </v-flex>
                            <v-flex xs3 class='text-xs-right'>
                                <a @click=''>
                                    <span class='mr-2'>@{{em.check_true_size}}/@{{em.check_size}}</span>
                                    <v-progress-circular rotate="-90" :value="em.check_true_size/em.check_size*100"
                                        color="primary" class='mr-2' width='7'></v-progress-circular>
                                </a>
                            </v-flex>
                        </v-layout>
                    </div>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-layout row wrap>
                                <v-flex xs12 v-if="checklists.hasOwnProperty(em.id)">
                                    <template v-if='checklists[em.id].length==0'>
                                        Nenhuma lista de tarefas foi adicionado para esse empregado.
                                    </template>
                                    <v-tabs v-model="model_tab_checklist" slider-color="black">
                                        <template>
                                            <v-tab v-for="c in checklists[em.id]">@{{getTemplate(c.checklist_template_id).name}}
                                                <v-icon @click='destroy_checklist(c.id)' class='ml-2 body-1'>clear</v-icon>
                                            </v-tab>
                                            <v-tab-item v-for="c in checklists[em.id]">
                                                <v-container grid-list-xs>
                                                    <v-layout row wrap>
                                                        <v-flex xs5>
                                                            <v-treeview :items="c.tree" open-all :active.sync='task_tree_active'
                                                                activatable active-class='extra-treeview'>
                                                                <template slot='prepend' slot-scope="{item}">
                                                                    <div>
                                                                        <v-checkbox v-if="item.status==-1" color="primary" v-model="item.status"
                                                                            indeterminate disabled></v-checkbox>
                                                                        <v-checkbox v-if="item.status==-2" color="primary" v-model="item.status"
                                                                            indeterminate disabled></v-checkbox>
                                                                        <v-checkbox v-else color="primary" v-model="item.status"
                                                                            @change="count_check(item.check_id,item.status,em.id)"></v-checkbox>
                                                                    </div>
                                                                </template>
                                                                <template v-if="item.status==-1" slot='append' slot-scope="{item}">
                                                                    <v-icon color="red">warning</v-icon>
                                                                </template>
                                                                <template v-if="item.status==-2" slot='append' slot-scope="{item}">
                                                                    <v-icon @click="app.notify('Esta tarefa depende de outra!','error')" color="green">error_outline</v-icon>
                                                                </template>
                                                            </v-treeview>
                                                        </v-flex>
                                                        <v-divider inset vertical></v-divider>
                                                        <template v-if='task_tree_selected!=0'>
                                                            <v-flex xs6>
                                                                <v-layout row wrap>
                                                                    <v-flex xs12 class='headline'>
                                                                        @{{task_tree_selected.name}}
                                                                    </v-flex>
                                                                    <v-flex xs12 class='font-weight-bold' color='red' v-if="check_tree_selected.status==-1">
                                                                        Expirou dia: @{{check_tree_selected.limit}}
                                                                    </v-flex>
                                                                    <v-flex xs12 class='font-weight-bold' v-else>
                                                                        Expira dia: @{{check_tree_selected.limit}}
                                                                    </v-flex>
                                                                    <v-flex xs12>
                                                                        <p class='body-2'>@{{task_tree_selected.description}}</p>
                                                                        <p class='caption mt-2'>Responsável:
                                                                            @{{resp.find(r=>r.id==check_tree_selected.resp).name}}
                                                                            <a class='ml-2' @click='dialog_responsavel=true;form.resp=parseInt(check_tree_selected.resp)'>
                                                                                <v-icon class='body-1' color='primary'>edit</v-icon>
                                                                            </a>
                                                                        </p>
                                                                        <v-divider class='mt-2'></v-divider>
                                                                    </v-flex>
                                                                    <v-flex xs12>
                                                                        <v-layout v-if='comments.length>0' row wrap>
                                                                            <template v-for='c in comments'>
                                                                                <v-flex xs2>
                                                                                    <v-avatar color="grey darken-4"
                                                                                        size='40'>
                                                                                        <span class="white--text headline">
                                                                                            @{{c.writer_name[0]}}</span>
                                                                                    </v-avatar>
                                                                                </v-flex>
                                                                                <v-flex xs10>
                                                                                    <v-layout row wrap>
                                                                                        <v-flex class='font-weight-bold'
                                                                                            xs12>
                                                                                            @{{c.writer_name}}
                                                                                        </v-flex>
                                                                                        <v-flex xs12 class='caption'
                                                                                            v-html="c.comment" style="white-space: pre-line;">
                                                                                        </v-flex>
                                                                                        <v-flex xs12 class='caption grey--text'>@{{c.created_at}}
                                                                                            <template v-if='c.editable'>
                                                                                                -
                                                                                                <a class='ml-2' @click='dialog_comment=true;form.comment=c.comment;form.comment_id=c.id'>
                                                                                                    <v-icon class='body-1'
                                                                                                        color='primary'>edit</v-icon>
                                                                                                </a>
                                                                                                <a class='ml-2' @click='destroy_comment(c.id)'>
                                                                                                    <v-icon class='body-1'
                                                                                                        color='primary'>delete</v-icon>
                                                                                                </a>
                                                                                            </template>
                                                                                        </v-flex>
                                                                                    </v-layout>
                                                                                    <v-divider></v-divider>
                                                                                </v-flex>
                                                                            </template>
                                                                        </v-layout>
                                                                        <template v-else>
                                                                            Nenhum comentário para essa tarefa.
                                                                        </template>
                                                                        <v-divider class='mt-2 mb-2'></v-divider>
                                                                    </v-flex>
                                                                    <v-flex xs12 class='text-xs-center'>
                                                                        <v-btn outline color="blue" dark @click='dialog_comment=true;form.comment="";form.comment_id=""'>+
                                                                            Adicionar comentário</v-btn>
                                                                    </v-flex>
                                                                </v-layout>
                                                            </v-flex>
                                                        </template>
                                                    </v-layout>
                                                </v-container>
                                            </v-tab-item>
                                        </template>
                                    </v-tabs>
                                </v-flex>
                                <v-flex xs12 v-else>
                                    Caregando...
                                </v-flex>
                            </v-layout>
                            <v-flex xs12 class='text-xs-right'>
                                <v-btn @click="form.id=em.id; popup(em.id)" slot="activator" color="green" outline>
                                    <v-icon dark class='mr-2'>assignment_ind</v-icon> Dados
                                </v-btn>
                                <v-btn @click="destroy(em.id)" color="red" outline>
                                    <v-icon dark class='mr-2'>delete</v-icon> Remover
                                </v-btn>
                                <v-btn @click="form.id=em.id; popup2(em.id)" color="blue" outline>
                                    <v-icon dark class='mr-2'>list</v-icon> Lista de tarefas
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-expansion-panel-content>
            </template>
            </v-expansion-panel>

            <!--POPUP 1 EMPLOYEE DATA-->
            <v-dialog v-model="dialog" max-width="500" r>
                <v-card>
                    <v-card-title>
                        <p style="width:100%" class="headline text-xs-center">Dados de Empregados</p>
                    </v-card-title>
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex xs6>Nome:</v-flex>
                            <v-flex xs6 class='font-weight-bold'>@{{form.name}}</v-flex>
                            <v-flex xs6>Perfil:</v-flex>
                            <v-flex xs6 class='font-weight-bold'>@{{form.profile}}</v-flex>
                            <v-flex xs6>CPF:</v-flex>
                            <v-flex xs6 class='font-weight-bold'>@{{form.cpf}}</v-flex>
                            <v-flex xs6>E-mail:</v-flex>
                            <v-flex xs6 class='font-weight-bold'>@{{form.email}}</v-flex>
                            <v-flex xs6>Telefone:</v-flex>
                            <v-flex xs6 class='font-weight-bold'>@{{form.fone}}</v-flex>
                            <v-flex xs6>Gestor</v-flex>
                            <v-flex xs6 class='font-weight-bold'>@{{gestor_name}}</v-flex>
                            <v-flex xs6>Data admissão</v-flex>
                            <v-flex xs6 class='font-weight-bold'>@{{form.created_at}}</v-flex>
                        </v-layout>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue" @click=" edit(form.id)" outline dark>
                            <v-icon class='mr-2'>edit</v-icon>Editar
                        </v-btn>
                        <v-btn color="red" @click="dialog = false" outline dark>
                            <v-icon class='mr-2'>close</v-icon>Fechar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!--POPUP 2 CHECKLIST DETAILS-->
            <v-dialog v-model="dialog2" max-width="700" r>
                <v-card>
                    <v-card-title>
                        <p style="width:100%" class="headline text-xs-center">Criar Lista de Tarefa</p>
                        <h2 style="width:100%" class="subheading text-xs-center">Ao criar uma lista, o criador é considerado gerenciador desta lista</h2>
                    </v-card-title>
                    <v-card-text>
                        <v-flex xs6>Lista de tarefas:</v-flex>
                        <v-select v-model="form.checklist_template_id" :items="templates" item-text="name" item-value="id"
                            label="Lista de tarefas" persistent-hint :rules='rules.profile' required></v-select>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue" @click="checklistTT(form.id); dialog2 = false" outline>
                            <v-icon dark class='mr-2'>add_circle_outline</v-icon>Criar
                        </v-btn>
                        <v-btn color="red" @click="dialog2 = false" outline>
                            <v-icon dark class='mr-2'>close</v-icon>Fechar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!--POPUP 3 CHECK MENU/RESP-->
            <v-dialog v-model="dialog_responsavel" max-width="600" v-if='task_tree_selected!=0'>
                <v-card>
                    <v-card-text>
                        <v-autocomplete v-model="form.resp" :items="resp" color="black" hide-no-data hide-selected
                            item-text="name" item-value="id" label="Responsável" prepend-icon="assignment_ind"></v-autocomplete>
                        <v-divider></v-divider>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="red" @click="updateCheck('RESP',check_tree_selected.id,null)" outline>Salvar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!--POPUP 3 CHECK MENU/COMMENT-->
            <v-dialog v-model="dialog_comment" max-width="600" r>
                <v-card>
                    <v-card-title>
                        <p style="width:100%" class="headline text-xs-center font-weight-bold">Comentario</p>
                    </v-card-title>
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-textarea height=100 v-model="form.comment" :rules="rules.comment" label="Comentário"
                                    required counter='300'></v-textarea>
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                        </v-layout>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="red" @click="dialog_comment = false" outline>
                            <v-icon dark class='mr-2'>close</v-icon>Fechar
                        </v-btn>
                        <v-btn color="blue" @click="store_comment()" outline>
                            <v-icon dark class='mr-2'>add_comment</v-icon>Comentar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-flex>
    </v-layout>


    <!-- EDIT/FORM -->
    <v-layout row wrap v-if="form_view">
        <v-flex xs12 sm6 offset-sm3>
            <v-card>
                <v-container grid-list-xs>
                    <div class='display-2'>@{{form_texts.title}}</div>
                    <v-form ref='form'>
                        <v-card-text>
                            <v-text-field v-model="form.name" :rules="rules.name" label="Name" required></v-text-field>
                            <v-text-field v-model="form.email" :rules="rules.email" label="E-mail" required></v-text-field>
                            <v-select v-model="form.site" :items="sites" item-text="complete_name" item-value="id"
                                :rules="rules.site" label="Site" persistent-hint required></v-select>
                            <v-text-field mask="###.###.###-##" return-masked-value="true" v-model="form.cpf" :rules="rules.cpf"
                                label="CPF" required></v-text-field>
                            <v-autocomplete v-model="form.resp" :items="resp" color="black" hide-no-data hide-selected
                                item-text="name" item-value="id" label="Gestor" prepend-icon="assignment_ind"></v-autocomplete>
                            <v-text-field mask="+##(##)#####-####" return-masked-value="true" v-model="form.fone"
                                :rules="rules.fone" label="Telefone Celular" required></v-text-field>
                            <v-select v-model="form.profile_id" :items="profiles" item-text="name" item-value="id"
                                label="Perfil" persistent-hint :rules='rules.profile' required></v-select>
                            <v-btn @click="store" color="primary">@{{form_texts.button}}</v-btn>
                        </v-card-text>
                    </v-form>
                </v-container>
            </v-card>
        </v-flex>
    </v-layout>

    @endsection

    @section('l-js')
    <script>
        Vue.component("page", {
            props: {
                screen: String
            },
            data() {
                return {
                    commentedit: -1,
                    filtros: ['Site', 'Gestor', 'Perfil' ,'Todos'],
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
                }
            },
            computed: {
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
                        switch(this.filtro.type){
                            case 'Gestor':
                                if(e.gestor==app.user.id){
                                    array.push(app.search_text(this.search,e.name));
                                }
                                else array.push(false);
                            break;
                            case 'Site':
                                if(e.site==this.filtro.site){
                                    array.push(app.search_text(this.search,e.name));
                                }
                                else array.push(false);
                            break;
                            case 'Perfil':
                                if(e.profile_id==this.filtro.profile){
                                    array.push(app.search_text(this.search,e.name));
                                }
                                else array.push(false);
                            break;
                            case 'Todos':
                                array.push(app.search_text(this.search,e.name));
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
                    this.form_view = true;
                    this.form_texts.title = "Criar Empregado";
                    this.form_texts.button = "Criar";
                    this.form = {
                        id: "",
                        name: '',
                        type: '',
                        dependences: '',
                        site: '',
                    }
                },
                store: function () {
                    if (this.$refs.form.validate()) {
                        app.confirm("Criando/Alterando Registro!", "Confirmar ação deste Registro?",
                            "green", () => {
                                $.ajax({
                                    url: "{{route('emp.store')}}",
                                    method: "POST",
                                    dataType: "json",
                                    headers: app.headers,
                                    data: this.form,
                                    success: (response) => {
                                        this.list();
                                        this.form_view = false;
                                        if (this.form.id == "") app.notify(
                                            "Empregado adicionado",
                                            "success");
                                        else app.notify("Edição salva", "success");
                                        if (this.form.id == "") app.notify(
                                            "Empregado adicionado com sucesso!",
                                            "success");
                                        else app.notify("Edição salva", "success");
                                    }
                                });
                            })
                    }
                },
                checklistTT: function (id) {
                    $.ajax({
                        url: "{{route('checklist_store')}}",
                        method: "POST",
                        dataType: "json",
                        headers: app.headers,
                        data: {
                            employee_id: id,
                            checklist_template_id: this.form.checklist_template_id,
                        },
                        error: (response) => {
                            app.notify('Ocorreu um erro! Tente novamente!','error');
                        },
                        success: (response) => {
                            this.list_checklist(id);
                            this.list();
                        }
                    });
                },
                list: function () {
                    $.ajax({
                        url: "{{route('emp.list')}}",
                        method: "GET",
                        dataType: "json",
                    }).done(response => {
                        this.employees = response;
                    });
                },
                list_admin: function () {
                    $.ajax({
                        url: "{{route('admin.list')}}",
                        method: "GET",
                        dataType: "json",
                    }).done(response => {
                        this.resp = response['admin_list'];
                        this.resp = this.resp.concat(response['resp_list']);
                        this.resp = this.resp.concat(response['default']);
                        this.list_group();
                    });
                },
                list_group: function(){
                    $.ajax({
                        url: "{{route('group.list')}}",
                        method: "GET",
                        dataType: "json",
                    }).done(response => {
                        for (r of response){
                            r.id = 'group'+r.id;
                        }
                        this.resp = this.resp.concat(response);
                    })
                },
                list_profile: function () {
                    $.ajax({
                        url: "{{route('profile.list')}}",
                        method: "GET",
                        dataType: "json",
                    }).done(response => {
                        this.profiles = response;
                    });
                },
                list_ChecklistTemplate: function () {
                    $.ajax({
                        url: "{{route('checklist.list')}}",
                        method: "GET",
                        dataType: "json",
                    }).done(response => {
                        this.templates = response;
                    });
                },
                list_checklist: function (employee_id) {
                    $.ajax({
                        url: "{{route('checklist.employee')}}",
                        method: "GET",
                        dataType: "json",
                        data: {
                            id: employee_id
                        }
                    }).done(response => {
                        this.checklists[employee_id] = response;
                        this.$forceUpdate();
                    });
                },
                list_sites: function () {
                    $.ajax({
                        url: "{{route('site.list')}}",
                        method: "GET",
                        dataType: "json",
                    }).done(response => {
                        this.sites = response;
                    });
                },
                list_comment: function (id) {
                    $.ajax({
                        url: "{{route('comment.list')}}",
                        method: "GET",
                        dataType: "json",
                        data: {
                            check_id: id,
                        }
                    }).done(response => {
                        this.comments = response;
                    });
                },
                list_tasks: function () {
                    $.ajax({
                        url: "{{route('task.list')}}",
                        method: "GET",
                        dataType: "json",
                    }).done(response => {
                        this.tasks = response;
                    });
                },
                store_comment: function () {
                    app.confirm("Escrevendo Comentário!", "Confirmar criação deste Comentário?", "green", () => {
                        $.ajax({
                            url: "{{route('comment.store')}}",
                            method: "POST",
                            dataType: "json",
                            headers: app.headers,
                            data: {
                                comment: this.form.comment,
                                comment_id: this.form.comment_id,
                                check_id: this.check_tree_selected.id
                            },
                            success: (response) => {
                                if (response['st'] == 'add') app.notify(
                                    "Comentário adicionado",
                                    "success");
                                else if (response['st'] == 'edit') app.notify(
                                    "comentário editado com sucesso!", "success");
                                this.list_comment(this.check_tree_selected.id);
                                this.dialog_comment = false;

                            }
                        });
                    })
                },
                getSiteName: function (id) {
                    for (i = 0; i < this.sites.length; i++) {
                        if (this.sites[i].id == id) return this.sites[i].complete_name;
                    }
                },
                edit: function (id) {
                    $.ajax({
                        url: "{{route('emp.edit')}}",
                        method: "GET",
                        dataType: "json",
                        data: {
                            id: id
                        },
                    }).done(response => {
                        this.form_texts.title = "Editar Perfil";
                        this.form_texts.button = "Salvar";
                        this.form = response;
                        this.gestor_name = response['gestor_name'];
                        this.form_view = true;
                        this.dialog = false;
                    });
                },
                popup: function (id) {
                    $.ajax({
                        url: "{{route('emp.edit')}}",
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
                destroy: function (id) {
                    app.confirm("Deletar esse empregado?",
                        "Todas as informações desse empregado serão deletadas.", "red", () => {
                            $.ajax({
                                url: "{{route('emp.remove')}}",
                                method: "DELETE",
                                dataType: "json",
                                headers: app.headers,
                                data: {
                                    id: id
                                },
                                success: (response) => {
                                    this.list();
                                    app.notify("Empregado removido", "error");
                                }
                            });
                        });
                },
                destroy_comment: function (id) {
                    app.confirm("Deletar esse comentário?",
                        "Após deletado esse cometário não poderá ser recuperado.", "red", () => {
                            $.ajax({
                                url: "{{route('comment.remove')}}",
                                method: "DELETE",
                                dataType: "json",
                                headers: app.headers,
                                data: {
                                    id: id
                                },
                                success: (response) => {
                                    this.list_comment(this.check_tree_selected.id);
                                    app.notify("Comentário removido", "error");
                                }
                            });
                        });


                },
                destroy_checklist: function (id) {
                    app.confirm("Remover lista de tarefa?",
                        "Todas as informações dessa lista serão deletadas.", "red", () => {
                            $.ajax({
                                url: "{{route('checklist.employee.remove')}}",
                                method: "DELETE",
                                dataType: "json",
                                headers: app.headers,
                                data: {
                                    checklist_id: id
                                },
                                success: (response) => {
                                    this.list_checklist(this.employee_selected.id);
                                    this.list();
                                    app.notify("Lista de tarefas removida", "error");
                                }
                            });
                        })

                },
                updateCheck: function (change_type, check_id, data,id) {
                    this.dialog_responsavel = false;
                    form_data = {
                        check_id: check_id
                    };
                    switch (change_type) {
                        case "RESP":
                            form_data.resp = this.form.resp;
                            this.check_tree_selected.resp = this.form.resp;
                            break;
                        case "STATUS":
                            form_data.status = data.status ? 1 : 0;
                            break;
                    }
                    $.ajax({
                        url: "{{route('check.edit')}}",
                        method: "POST",
                        dataType: "json",
                        headers: app.headers,
                        data: form_data
                    }).done(response => {
                        if(response['error']==false)app.notify("Tarefa modificada!", "success");
                        this.list_checklist(id);
                    });
                },
                count_check: function (check_id, check_status,id) {
                    if (check_status) {
                        this.employee_selected.check_true_size++;
                    } else if (!status) {
                        this.employee_selected.check_true_size--;
                    }
                    this.updateCheck("STATUS", check_id, {
                        status: check_status
                    },id);

                },
                remove(item) {
                    const index = this.friends.indexOf(item.name)
                    if (index >= 0) this.friends.splice(index, 1)
                },
                getTemplate(id) {
                    for (t of this.templates) {
                        if (t.id == id) return t;
                    }
                    return null;
                },
                getTask: function (id) {
                    for (j = 0; j < this.tasks.length; j++) {
                        if (id == this.tasks[j].id) return this.tasks[j]
                    }
                    return null;
                },
                getEmployee: function (id) {
                    for (j = 0; j < this.employees.length; j++) {
                        if (id == this.employees[j].id) return this.employees[j];
                    }
                    return null;
                },
                getCheckByTask: function (id) {
                    for (j = 0; j < this.checklist_selected.checks.length; j++) {
                        if (id == this.checklist_selected.checks[j].task_id) return this.checklist_selected.checks[j]
                    }
                    return null;
                },
                siteName: function (id) {
                    site = this.sites.find(s => s.id == id);
                    if (site) return site.complete_name;
                    else return "";
                },
                searching: function (search) {
                    this.search = search;
                },
                mounted: function () {
                    app.setMenu('employee');
                    this.filtro.site = parseInt(app.user.site);
                }
            },
            mounted() {
                this.list_profile();
                this.list_ChecklistTemplate();
                this.list_sites();
                this.list_admin();
                this.list_tasks();
                this.list();
            }
        });
    </script>







    @endsection
