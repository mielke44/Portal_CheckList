@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Empregados')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap v-if="!form_view">
        <v-flex class='text-xs-right'>
            <v-btn @click="add()" color="primary">Adicionar empregado</v-btn>
        </v-flex>
        <v-flex xs12>
            <v-expansion-panel v-model='model_employee'>
                <v-expansion-panel-content v-for='em in employees'>
                    <div slot="header">
                        <v-layout row wrap fill-height align-center>
                            <v-flex xs6>
                                @{{em.name}}
                            </v-flex>
                            <v-flex xs3>
                                @{{em.profile}}
                            </v-flex>
                            <v-flex xs3 class='text-xs-right'>
                                <a @click=''>
                                    <span class='mr-2'>@{{em.check_true_size}}/@{{em.check_size}}</span>
                                    <v-progress-circular rotate="-90" :value="em.check_true_size/em.check_size*100" color="primary"
                                        class='mr-2' width='7'></v-progress-circular>
                                </a>
                            </v-flex>
                        </v-layout>
                    </div>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-layout row wrap>
                                <v-flex xs12 v-if="checklists.hasOwnProperty(em.id)">
                                    <v-tabs v-model="tab" slider-color="black">
                                        <v-tab  v-for="c in checklists[em.id]">@{{c.name[0].name}}</v-tab>
                                            <v-tab-item>
                                                <v-tab-items v-for='ch in check'>
                                                    <v-divider></v-divider>
                                                        <v-layout row wrap>
                                                            <v-flex xs1>
                                                                <v-checkbox
                                                                    v-model="ch.status"
                                                                    @change="count_check(ch.id,em,ch.status)"
                                                                    color="green"
                                                                ></v-checkbox>
                                                            </v-flex>
                                                            <v-flex xs3 class="font-weight-bold">@{{ch.name[0].name}}</v-flex>
                                                            <v-flex xs6 class='caption'>@{{ch.description[0].description}}</v-flex>
                                                            <v-btn small fab color="blue" @click="form.id = ch.id;popup3(ch.id)" dark><v-icon>edit</v-icon></v-btn>
                                                        </v-layout>
                                                </v-tab-items>
                                            </v-tab-item>
                                    </v-tabs>
                                </v-flex>
                            </v-layout>
                            <v-flex xs12 class='text-xs-right'>
                                <v-btn @click="form.id=em.id; popup(em.id)" slot="activator" color="green" outline>
                                    <v-icon dark class='mr-2'>assignment_ind</v-icon> Dados
                                </v-btn>
                                <v-btn @click="destroy(em.id)" color="red" outline>
                                    <v-icon dark class='mr-2'>delete</v-icon> Remover
                                </v-btn>
                                <v-btn @click="form.id=em.id; popup2(em.id)" color="yellow" outline>
                                    <v-icon dark class='mr-2'>list</v-icon> Lista de tarefas
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-expansion-panel-content>
            </v-expansion-panel>


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


            <v-dialog v-model="dialog2" max-width="700" r>
                <v-card>
                    <v-card-title>
                        <p style="width:100%" class="headline text-xs-center">Checklist</p>
                    </v-card-title>
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex xs6>Lista de tarefas:</v-flex>
                            <v-select v-model="form.checklist_template_id" :items="templates" item-text="name"
                                item-value="id" label="Lista de tarefas" persistent-hint :rules='rules.profile'
                                required></v-select>
                        </v-layout>
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


            <v-dialog v-model="dialog3" max-width="600" r>
                <v-card>
                    <v-card-title>
                        <p style="width:100%" class="headline text-xs-center font-weight-bold">Menu de Tarefa</p>
                    </v-card-title>
                    <v-card-text>
                        <v-layout row wrap>
                            <v-autocomplete
                                v-model="form.resp"
                                :items="resp"
                                color="black"
                                hide-no-data
                                hide-selected
                                item-text="name"
                                item-value="id"
                                label="Responsável"
                                prepend-icon="mdi-database-search"
                                return-object
                            ></v-autocomplete>
                            <v-btn color="red" @click="update(form.id,'','resp')" outline>Salvar</v-btn>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-textarea  height=100 v-model="form.comment" :rules="rules.comment" label="Comentário" required counter='300'></v-textarea>
                                <v-btn color="blue" @click="add_comment(form.id,'')" outline>
                                    <v-icon dark class='mr-2'>add_comment</v-icon>Comentar
                                </v-btn>
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-flex xs3>Comentários:</v-flex>
                            <v-list two-line dense>
                            <template v-for='(c,i) in comments'>
                                <v-divider></v-divider>
                                <v-list-tile ripple>
                                <v-list-tile-content>
                                    <v-flex xs9>
                                        <v-list-tile-title>@{{c.writer_name}}</v-list-tile-title>
                                        <v-list-tile-sub-title v-if="commentedit==i">
                                            <v-text-field  small v-model="form.commentedit" :label="c.comment" clearable></v-text-field> 
                                        </v-list-tile-sub-title>
                                        <v-list-tile-sub-title v-if="commentedit!=i">@{{c.comment}}</v-list-tile-sub-title>
                                    </v-flex>
                                </v-list-tile-content>
                                <v-list-tile-action>
                                    <v-flex xs12>
                                        <v-btn v-if="commentedit!=i" small fab color="blue" @click="commentedit=i" dark>
                                            <v-icon>edit</v-icon>
                                        </v-btn>
                                        <v-btn v-if="commentedit==i" small fab color="green" @click="add_comment(form.id,c.id)" dark>
                                            <v-icon >done</v-icon>
                                        </v-btn>
                                        <v-btn v-if="commentedit!=i" small fab color="red" @click="destroy_comment(c.id)" dark>
                                            <v-icon >delete</v-icon>
                                        </v-btn>
                                        <v-btn v-if="commentedit==i" small fab color="red" @click="commentedit=false" dark>
                                            <v-icon >cancel</v-icon>
                                        </v-btn>
                                    </v-flex>
                                </v-list-tile-action>
                            </template>
                            </v-list>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="red" @click="dialog3 = false" outline>
                            <v-icon dark class='mr-2'>close</v-icon>Fechar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-flex>
    </v-layout>


    <!-- EDIT -->
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
                    commentedit:-1,
                    model_employee: null,
                    dialog: false,
                    dialog2: false,
                    dialog3: false,
                    employees: [],
                    dependencies: [],
                    profiles: [],
                    checklists: {},
                    check: [],
                    check_size:"",
                    templates: [],
                    sites: [],
                    form_view: false,
                    tab: null,
                    resp: {},
                    comments: [],
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
                        commentedit:'',
                        resp: '',
                    },
                    items: [{
                            text: 'Efetivado',
                            value: "1",
                        },
                        {
                            text: 'Estagiário',
                            value: "2",
                        }
                    ],
                }
            },
            watch: {
                model_employee: function (val) {
                    if (val != null) this.list_checklist(this.employees[val].id);
                }
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
                        $.ajax({
                            url: "{{route('emp.store')}}",
                            method: "POST",
                            dataType: "json",
                            headers: app.headers,
                            data: this.form,
                            success: (response) => {
                                this.list();
                                this.form_view = false;
                                if (this.form.id == "") app.notify("Empregado adicionado",
                                    "success");
                                else app.notify("Edição salva", "success");
                                if (this.form.id == "") app.notify(
                                    "Empregado adicionado com sucesso!", "success");
                                else app.notify("Edição salva", "success");
                            }
                        });
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
                    }).done(response =>{
                        this.list_checklist(this.employees[val].id);
                    })
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
                list_admin: function(){
                    $.ajax({
                        url: "{{route('admin.list')}}",
                        method: "GET",
                        dataType: "json",
                    }).done(response => {
                        this.resp = response['list'];
                    });
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
                    if (!this.checklists.hasOwnProperty(employee_id)) {
                        $.ajax({
                            url: "{{route('checklist.employee')}}",
                            method: "GET",
                            dataType: "json",
                            data: {
                                id: employee_id
                            }
                        }).done(response => {
                            this.checklists[employee_id] = response['checklists'];
                            this.check = response['check'][0];
                            for (i = 0; i < response['check'][0][0]; i++) {
                                this.comments.push(response['check'][0][0]['comment']);
                            }
                            //alert(JSON.stringify(response['check'][0][0]));
                            var temp = this.model_employee;
                            this.model_employee = null;
                            this.model_employee = temp;
                        });
                    }
                },
                list_sites: function (){
                    $.ajax({
                        url: "{{route('site.list')}}",
                        method: "GET",
                        dataType: "json",
                    }).done(response => {
                        this.sites = response;
                    });
                },
                list_comment: function(id){
                    $.ajax({
                        url: "{{route('comment.list')}}",
                        method: "GET",
                        dataType: "json",
                        data: {
                            check_id: id,
                        }
                    }).done(response => {
                        this.comments = response['comment'];
                    });
                },
                add_comment: function(id,id2){
                    $.ajax({
                            url: "{{route('comment.store')}}",
                            method: "POST",
                            dataType: "json",
                            headers: app.headers,
                            data:{ 
                                form: this.form,
                                check_id: id,
                                comment_id: id2
                                },
                                
                            success: (response) => {
                                this.commentedit=-1;
                                if (response['st']=='add') app.notify("Comentário adicionado","success");
                                else if(response['st']=='edit')app.notify("comentário editado com sucesso!", "success");
                                this.list_comment(id);
                                

                            }
                        });
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
                        this.form_view = true;
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
                },
                destroy_comment: function (id) {
                    $.ajax({
                        url: "{{route('comment.remove')}}",
                        method: "DELETE",
                        dataType: "json",
                        headers: app.headers,
                        data: {
                            id: id
                        },
                        success: (response) => {
                            this.list_comment();
                            app.notify("Comentário removido", "error");
                        }
                    });
                },
                update: function(check_id,status,change_type){
                    $.ajax({
                        url: "{{route('check.edit')}}",
                        method: "POST",
                        dataType: "json",
                        headers: app.headers,
                        data:{
                            form: this.form,
                            check_id: check_id,
                            status: status,
                            change_type: change_type,
                            
                        },
                    }).done(response => {
                        this.form_view = false;
                        app.notify("Tarefa modificada!", "success");
                    });
                },
                count_check: function(check_id,em,status){
                    if(status==1 || status=='true'){
                        alert(status);
                        this.update(check_id,status,'status')
                        em.check_true_size++;
                    }
                    else if(status==0 || status=='false'){
                        alert(status);
                        em.check_true_size--;
                        this.update(check_id,status,'status')
                    }
                },
                remove (item) {
                const index = this.friends.indexOf(item.name)
                if (index >= 0) this.friends.splice(index, 1)
                },
            },
            mounted() {
                this.list();
                this.list_profile();
                this.list_ChecklistTemplate();
                this.list_sites();
                this.list_admin();
                setTimeout(() => {
                    app.screen = 1
                }, 1);
            }
        });
    </script>
    @endsection
