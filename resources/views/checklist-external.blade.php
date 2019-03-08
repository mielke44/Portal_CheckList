@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Lista de tarefas')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap>
        <v-flex xs12>
            <v-flex xs6 sm6 class="py-2">
                <v-btn-toggle v-model="view.filter_type" mandatory>
                    <v-btn flat>
                        <v-icon>priority_high</v-icon>
                        Pendentes
                    </v-btn>
                    <v-btn flat>
                        <v-icon>history</v-icon>
                        Histórico
                    </v-btn>
                </v-btn-toggle>
            </v-flex>
        <!--FILTRO PENDENTE-->
            <v-expansion-panel v-if="view.filter_type==0" v-model='model_checks' readonly>
                <v-subheader>Tarefas de grupo</v-subheader>
                <v-expansion-panel-content v-for='(c,i) in models.check.list'>
                    <div v-if="(c.status== 0 || c.status==-2) && c.resp.length>5" slot="header">
                        <v-layout row wrap fill-height align-center>
                            <v-flex xs1>
                                <v-checkbox v-if="c.status==-1" color="primary" v-model="c.status"
                                    indeterminate disabled></v-checkbox>
                                <v-checkbox v-else-if="c.status==-2" color="primary" v-model="c.status"
                                    indeterminate disabled></v-checkbox>
                                <v-checkbox v-else color="primary" v-model="c.status"
                                    @change="updateCheck(c)"></v-checkbox>
                            </v-flex>
                            <v-flex xs1>
                                <template v-if="c.status==-2">
                                    <v-icon @click="app.notify('Esta tarefa depende de outra!','warning')" color="green">error_outline</v-icon>
                                </template>
                                <template v-if="c.status==-1">
                                    <v-icon @click="app.notify('Esta tarefa expirou!','error')" color="red">warning</v-icon>
                                </template>
                            </v-flex>
                            <v-flex xs5 @click='model_checks=model_checks==i?-1:i'>
                                @{{get_model(models.task,c.task_id).name}}
                            </v-flex>
                            <v-flex xs5 @click='model_checks=model_checks==i?-1:i'>
                                @{{c.user}}
                            </v-flex>
                        </v-layout>
                    </div>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-flex xs6>
                                <p class='font-weight-bold'>Descrição</p>
                                @{{get_model(models.task,c.task_id).description}}
                            </v-flex>
                            <v-flex xs6>
                                <v-layout row wrap>
                                    <v-flex xs12 class='font-weight-bold' color='red' v-if="c.status==-1">
                                        Expirou dia: @{{c.limit}}
                                    </v-flex>
                                    <v-flex xs12 class='font-weight-bold' v-else>
                                        Expira dia: @{{c.limit}}
                                    </v-flex>
                                    <v-flex xs12>
                                        <p class='font-weight-bold'>Comentários</p>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-layout v-if='comments.length>0' row wrap>
                                            <template v-for='c in comments'>
                                                <v-flex xs2>
                                                    <v-avatar color="grey darken-4" size='40'>
                                                        <span class="white--text headline">
                                                            @{{c.writer_name[0]}}</span>
                                                    </v-avatar>
                                                </v-flex>
                                                <v-flex xs10>
                                                    <v-layout row wrap>
                                                        <v-flex class='font-weight-bold' xs12>
                                                            @{{c.writer_name}}
                                                        </v-flex>
                                                        <v-flex xs12 class='caption' v-html="c.comment" style="white-space: pre-line;">
                                                        </v-flex>
                                                        <v-flex xs12 class='caption grey--text'>@{{c.created_at}}
                                                            <template v-if='c.editable'>
                                                                -
                                                                <a class='ml-2' @click='dialog_comment=true;form.comment=c.comment;form.comment_id=c.id'>
                                                                    <v-icon class='body-1' color='primary'>edit</v-icon>
                                                                </a>
                                                                <a class='ml-2' @click='destroy_comment(c.id)'>
                                                                    <v-icon class='body-1' color='primary'>delete</v-icon>
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
                                            Adicionar
                                            comentário</v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-expansion-panel-content>
                <v-subheader>Tarefas para seu usuário</v-subheader>
                <v-expansion-panel-content v-for='(c,i) in models.check.list'>
                        <div v-if="(c.status== 0 || c.status==-2) && c.resp.length<5" slot="header">
                            <v-layout row wrap fill-height align-center>
                                <v-flex xs1>
                                    <v-checkbox v-if="c.status==-1" color="primary" v-model="c.status"
                                        indeterminate disabled></v-checkbox>
                                    <v-checkbox v-else-if="c.status==-2" color="primary" v-model="c.status"
                                        indeterminate disabled></v-checkbox>
                                    <v-checkbox v-else color="primary" v-model="c.status"
                                        @change="updateCheck(c)"></v-checkbox>
                                </v-flex>
                                <v-flex xs1>
                                    <template v-if="c.status==-2">
                                        <v-icon @click="app.notify('Esta tarefa depende de outra!','warning')" color="green">error_outline</v-icon>
                                    </template>
                                    <template v-if="c.status==-1">
                                        <v-icon @click="app.notify('Esta tarefa expirou!','error')" color="red">warning</v-icon>
                                    </template>
                                </v-flex>
                                <v-flex xs5 @click='model_checks=model_checks==i?-1:i'>
                                    @{{get_model(models.task,c.task_id).name}}
                                </v-flex>
                                <v-flex xs5 @click='model_checks=model_checks==i?-1:i'>
                                    @{{c.user}}
                                </v-flex>
                            </v-layout>
                        </div>
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-flex xs6>
                                    <p class='font-weight-bold'>Descrição</p>
                                    @{{get_model(models.task,c.task_id).description}}
                                </v-flex>
                                <v-flex xs6>
                                    <v-layout row wrap>
                                        <v-flex xs12 class='font-weight-bold' color='red' v-if="c.status==-1">
                                            Expirou dia: @{{c.limit}}
                                        </v-flex>
                                        <v-flex xs12 class='font-weight-bold' v-else>
                                            Expira dia: @{{c.limit}}
                                        </v-flex>
                                        <v-flex xs12>
                                            <p class='font-weight-bold'>Comentários</p>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-layout v-if='comments.length>0' row wrap>
                                                <template v-for='c in comments'>
                                                    <v-flex xs2>
                                                        <v-avatar color="grey darken-4" size='40'>
                                                            <span class="white--text headline">
                                                                @{{c.writer_name[0]}}</span>
                                                        </v-avatar>
                                                    </v-flex>
                                                    <v-flex xs10>
                                                        <v-layout row wrap>
                                                            <v-flex class='font-weight-bold' xs12>
                                                                @{{c.writer_name}}
                                                            </v-flex>
                                                            <v-flex xs12 class='caption' v-html="c.comment" style="white-space: pre-line;">
                                                            </v-flex>
                                                            <v-flex xs12 class='caption grey--text'>@{{c.created_at}}
                                                                <template v-if='c.editable'>
                                                                    -
                                                                    <a class='ml-2' @click='dialog_comment=true;form.comment=c.comment;form.comment_id=c.id'>
                                                                        <v-icon class='body-1' color='primary'>edit</v-icon>
                                                                    </a>
                                                                    <a class='ml-2' @click='destroy_comment(c.id)'>
                                                                        <v-icon class='body-1' color='primary'>delete</v-icon>
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
                                                Adicionar
                                                comentário</v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                            </v-layout>
                        </v-container>
                </v-expansion-panel-content>
                <v-expansion-panel-content v-if='models.check.list.length==0'>
                    <div slot="header">Nenhuma tarefa está desiginada para você</div>
                </v-expansion-panel-content>
            </v-expansion-panel>
        <!--FILTRO HISTÓRICO-->
            <v-expansion-panel v-if="view.filter_type==1" v-model='model_checks' readonly>
                <v-subheader>Tarefas de grupo</v-subheader>
                <v-expansion-panel-content v-for='(c,i) in models.check.list'>
                    <div v-if="(c.status==1 || c.status==-1) && c.resp.length>5" slot="header">
                        <v-layout row wrap fill-height align-center>
                            <v-flex xs1>
                                <v-checkbox v-if="c.status==-1" color="primary" v-model="c.status"
                                    indeterminate disabled></v-checkbox>
                                <v-checkbox v-else-if="c.status==-2" color="primary" v-model="c.status"
                                    indeterminate disabled></v-checkbox>
                                <v-checkbox v-else color="primary" v-model="c.status"
                                    @change="updateCheck(c)"></v-checkbox>
                            </v-flex>
                            <v-flex xs1>
                                <template v-if="c.status==-2">
                                    <v-icon @click="app.notify('Esta tarefa depende de outra!','warning')" color="green">error_outline</v-icon>
                                </template>
                                <template v-if="c.status==-1">
                                    <v-icon @click="app.notify('Esta tarefa expirou!','error')" color="red">warning</v-icon>
                                </template>
                            </v-flex>
                            <v-flex xs5 @click='model_checks=model_checks==i?-1:i'>
                                @{{get_model(models.task,c.task_id).name}}
                            </v-flex>
                            <v-flex xs5 @click='model_checks=model_checks==i?-1:i'>
                                @{{c.user}}
                            </v-flex>
                        </v-layout>
                    </div>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-flex xs6>
                                <v-layout row wrap>
                                    <v-flex xs6>
                                        <p class='font-weight-bold'>Descrição</p>
                                        @{{get_model(models.task,c.task_id).description}}
                                    </v-flex>
                                    <v-flex xs6>
                                            <p class='font-weight-bold'>Alterada por:</p>
                                            @{{get_model(models.user,c.completed).name}}
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex xs6>
                                <v-layout row wrap>
                                    <v-flex xs12 class='font-weight-bold' color='red' v-if="c.status==-1">
                                        Expirou dia: @{{c.limit}}
                                    </v-flex>
                                    <v-flex xs12 class='font-weight-bold' v-else>
                                        Expira dia: @{{c.limit}}
                                    </v-flex>
                                    <v-flex xs12>
                                        <p class='font-weight-bold'>Comentários</p>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-layout v-if='comments.length>0' row wrap>
                                            <template v-for='c in comments'>
                                                <v-flex xs2>
                                                    <v-avatar color="grey darken-4" size='40'>
                                                        <span class="white--text headline">
                                                            @{{c.writer_name[0]}}</span>
                                                    </v-avatar>
                                                </v-flex>
                                                <v-flex xs10>
                                                    <v-layout row wrap>
                                                        <v-flex class='font-weight-bold' xs12>
                                                            @{{c.writer_name}}
                                                        </v-flex>
                                                        <v-flex xs12 class='caption' v-html="c.comment" style="white-space: pre-line;">
                                                        </v-flex>
                                                        <v-flex xs12 class='caption grey--text'>@{{c.created_at}}
                                                            <template v-if='c.editable'>
                                                                -
                                                                <a class='ml-2' @click='dialog_comment=true;form.comment=c.comment;form.comment_id=c.id'>
                                                                    <v-icon class='body-1' color='primary'>edit</v-icon>
                                                                </a>
                                                                <a class='ml-2' @click='destroy_comment(c.id)'>
                                                                    <v-icon class='body-1' color='primary'>delete</v-icon>
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
                                            Adicionar
                                            comentário</v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-expansion-panel-content>
            </v-expansion-panel>

        </v-flex>
    </v-layout>

</v-container>


<v-dialog v-model="dialog_comment" max-width="600" r>
    <v-card>
        <v-card-title>
            <p style="width:100%" class="headline text-xs-center font-weight-bold">Comentario</p>
        </v-card-title>
        <v-card-text>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-textarea height=100 v-model="form.comment" label="Comentário" required counter='300'></v-textarea>
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


@endsection

@section('l-js')
<script src='{{asset("sources/comments.js")}}'></script>
<script src='{{asset("sources/employees.js")}}'></script>
<script src='{{asset("sources/tasks.js")}}'></script>
<script src='{{asset("sources/checks.js")}}'></script>
<script src='{{asset("sources/users.js")}}'></script>

<script>
        vue_page = {
            mixins: [sources_checks,sources_users, sources_employees, sources_tasks, sources_comments],
            props: {
                screen: String
        },
        data() {
            return {
                view: {
                    filter_type: 0,
                },
                model_checks: null,
                model_2: null,
                tasks: [],
                checklists: [],
                checks: [],
                dialog_comment: false,
                comments: [],
                editable:false,
                form: {
                    comment: '',
                    comment_id: '',
                },
                search:'',
            }
        },
        computed:{
            message: function(){
                for(c of this.models.check.list){
                    if(c.status!=1 || c.status!=1)return true;
                }return false;
            }
        },
        watch: {
            model_checks: function (val) {
                this.list_comment(this.checks[val].id);
            },
        },
        methods: {
            store_comment: function () {
                $.ajax({
                    url: "route('comment.store')",
                    method: "POST",
                    dataType: "json",
                    headers: app.headers,
                    data: {
                        comment: this.form.comment,
                        comment_id: this.form.comment_id,
                        check_id: this.checks[this.model_checks].id
                    },
                    success: (response) => {
                        if (response['st'] == 'add') app.notify("Comentário adicionado",
                            "success");
                        else if (response['st'] == 'edit') app.notify(
                            "comentário editado com sucesso!", "success");
                        this.list_comment(this.checks[this.model_checks].id);
                        this.dialog_comment = false;
                    }
                });
            },
            destroy_comment: function (id) {
                app.confirm("Deletar esse comentário?",
                    "Após deletado esse cometário não poderá ser recuperado.", "yellow darken-3", () => {
                        $.ajax({
                            url: "route('comment.remove')",
                            method: "DELETE",
                            dataType: "json",
                            headers: app.headers,
                            data: {
                                id: id
                            },
                            success: (response) => {
                                this.list_comment(this.checks[this.model_checks].id);
                                app.notify("Comentário removido", "error");
                            }
                        });
                    });
            },
            updateCheck: function (check) {
                this.store_model(this.models.check,check);
            },
            searching: function (search) {
                    this.search = search;
            },
        },
        mounted() {
            this.list_model(this.models.task);
            this.list_model(this.models.employee);
            this.list_model(this.models.check,{'your': 1});
            this.list_model(this.models.comment);
        }
    };
</script>
@endsection
