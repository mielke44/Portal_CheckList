@extends('layouts.default.index') @section('title','Portal Checklist')
@section('l-title','Lista de tarefas') @section('l-content')

<v-container grid-list-lg>
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
        </v-flex>
        <!--FILTRO PENDENTE-->
        <v-flex xs12>
            <v-card height="100%">
                <v-list row wrap class="pa-0">
                    <v-item-group v-model="view.selected_task">
                        <v-subheader>Suas Tarefas</v-subheader>
                        <template v-for="(c,i) in checks_resp">
                            <v-item>
                                <v-list-tile xs12 slot-scope="{ active, toggle }" @click="view.selected_check=c.id">
                                    <v-list-tile-content @click="toggle">
                                        <v-list-title-title :class="active?'red--text':''">
                                            @{{
                                            get_model(
                                            models.task,
                                            c.task_id
                                            ).name
                                            }}
                                            <span class='grey--text'>(@{{check_get_status(c.status)}})</span>
                                        </v-list-title-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                            </v-item>
                            <v-divider></v-divider>
                        </template>
                        <v-subheader>Tarefas de grupo</v-subheader>
                        <template v-for="(c,i) in checks_group">
                            <v-item>
                                <v-list-tile xs12 slot-scope="{ active, toggle }" @click="view.selected_check=c.id">
                                    <v-list-tile-content @click="toggle">
                                        <v-list-title-title :class="active?'red--text':''">
                                            @{{
                                            get_model(
                                            models.task,
                                            c.task_id
                                            ).name
                                            }}
                                            <span class='grey--text'>(@{{check_get_status(c.status)}})</span>
                                        </v-list-title-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                            </v-item>
                            <v-divider></v-divider>
                        </template>
                    </v-item-group>
                </v-list>
            </v-card>
        </v-flex>
    </v-layout>

    <v-navigation-drawer absolute v-model="drawer_task" right width="400">
        <template v-if="selected_task.task_id != 0">
            <v-container grid-list-xs>
                <v-layout row wrap :set="task=get_model(models.task,selected_task.task_id)">
                    <v-flex xs12 class="text-xs-center headline">
                        @{{ task.name }}
                    </v-flex>
                    <v-flex xs12 class="text-xs-center">
                        @{{ task.description }}
                    </v-flex>
                    <v-flex xs12>
                        <v-divider></v-divider>
                    </v-flex>
                    <v-flex xs6 class="font-weight-bold">
                        Situação:
                    </v-flex>
                    <v-flex xs6>
                        @{{check_get_status(c.status)}}
                    </v-flex>
                    <template v-if='selected_task.completed!=null'>
                        <v-flex xs6 class="font-weight-bold">
                            Finalizada por:
                        </v-flex>
                        <v-flex xs6>@{{get_model(models.user,selected_task.completed).name}}</v-flex>
                    </template>
                    <v-flex xs6 class="font-weight-bold">
                        Tarefa criada em:
                    </v-flex>
                    <v-flex xs6>
                        @{{selected_task.created_at}}
                    </v-flex>
                    <v-flex xs6 class="font-weight-bold">
                        Data limite:
                    </v-flex>
                    <v-flex xs6>
                        @{{selected_task.limit}}
                    </v-flex>
                    <v-flex xs12>
                        <v-divider></v-divider>
                    </v-flex>
                    <template v-if='selected_task.status==0'>
                        <v-flex xs12>
                            <v-btn color="green" dark block @click='updateCheck(selected_task)'>
                                <v-icon class='mr-2'>check_circle</v-icon> Finalizar tarefa
                            </v-btn>
                        </v-flex>
                    </template>
                    <template v-for='c in models.comment.list'>
                        <v-flex xs2 :set="writer=get_model(models.user,c.writer)">
                            <v-avatar size='30' color="primary" class='white--text'>
                                @{{writer.name[0]}}
                            </v-avatar>
                        </v-flex>
                        <v-flex xs10>
                            <v-layout row wrap>
                                <v-flex xs12 class='body-2'>
                                    @{{writer.name}}
                                </v-flex>
                                <v-flex class='body-1' xs12>
                                    @{{c.comment}}
                                </v-flex>
                                <v-flex xs8 class='grey--text caption'>
                                    @{{c.updated_at}}
                                </v-flex>
                                <v-flex xs4>
                                    <v-icon color='primary' small @click='edit_comment(c)'>edit</v-icon>
                                    <v-icon color='primary' small @click='destroy_comment(c.id)'>delete_outline</v-icon>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <v-flex xs12>
                            <v-divider></v-divider>
                        </v-flex>
                    </template>
                    <v-flex xs12>
                        <v-textarea ref='textarea_comment' v-model='view.comment.msg' label="Comentário" hint="Digite seu comentário"></v-textarea>
                    </v-flex>
                    <v-flex v-if='view.comment.id==0' xs12>
                        <v-btn color="primary" block @click='store_comment()' id='comment'>Enviar</v-btn>
                    </v-flex>
                    <template v-else>
                        <v-flex xs6>
                            <v-btn color="red" block @click='view.comment.id=0;view.comment.msg=""' dark>Cancelar</v-btn>
                        </v-flex>
                        <v-flex xs6>
                            <v-btn color="green" block @click='store_comment()' dark id='comment'>Salvar</v-btn>
                        </v-flex>
                    </template>
                </v-layout>
            </v-container>
        </template>
    </v-navigation-drawer>
</v-container>


@endsection @section('l-js')
<script src="{{ asset('sources/comments.js') }}"></script>
<script src="{{ asset('sources/employees.js') }}"></script>
<script src="{{ asset('sources/tasks.js') }}"></script>
<script src="{{ asset('sources/checks.js') }}"></script>
<script src="{{ asset('sources/users.js') }}"></script>

<script>
    vue_page = {
        mixins: [
            sources_checks,
            sources_users,
            sources_employees,
            sources_tasks,
            sources_comments
        ],
        props: {
            screen: String
        },
        data() {
            return {
                view: {
                    selected_check: -1,
                    selected_task: -1,
                    filter_type: 0,
                    check_resp: 0,
                    check_group: 0,
                    comment: {
                        msg: "",
                        id: "",
                    }
                },
                search: ""
            };
        },
        computed: {
            checks_group: function () {
                var array = [];
                for (c of this.models.check.list) {
                    if (c.resp.indexOf("group") > -1 & this.filter_show(c.status)) array.push(c);
                }
                return array;
            },
            checks_resp: function () {
                var array = [];
                for (c of this.models.check.list) {
                    if (c.resp.indexOf("group") == -1 & this.filter_show(c.status)) {
                        array.push(c);
                    }
                }
                return array;
            },
            selected_task: function () {
                if (
                    this.view.selected_task == -1 ||
                    typeof this.view.selected_task == "undefined"
                )
                    return {
                        task_id: 0
                    };
                return this.get_model(this.models.check, this.view.selected_check);

            },
            drawer_task: function () {
                if (
                    this.view.selected_task == -1 ||
                    typeof this.view.selected_task == "undefined"
                )
                    return false;
                else return true;
            }
        },
        watch: {
            selected_task: function (v) {
                this.list_model(this.models.comment, {
                    check_id: v.id
                })
            },
            "view.filter_type": function () {
                this.view.selected_task = -1;
            }
        },
        methods: {
            store_comment: function () {
                if (this.view.comment.msg != "") {
                    this.store_model(this.models.comment, {
                        comment: this.view.comment.msg,
                        comment_id: this.view.comment.id,
                        check_id: this.selected_task.id
                    }, response => {
                        this.view.comment.msg = "";
                        this.view.comment.id = "";
                        if (response["st"] == "add")
                            this.notify("Comentário adicionado", "success");
                        else if (response["st"] == "edit")
                            this.notify(
                                "comentário editado com sucesso!",
                                "success"
                            );
                        this.list_model(this.models.comment, {
                            check_id: this.selected_task.id
                        });
                    });
                }

            },
            destroy_comment: function (id) {
                this.confirm(
                    "Deletar esse comentário?",
                    "Após deletado esse cometário não poderá ser recuperado.",
                    "red darken-3",
                    () => {
                        this.destroy_model(this.models.comment, id, response => {
                            this.list_model(this.models.comment, {
                                check_id: this.selected_task.id
                            });
                            this.notify("Comentário removido", "error");
                        })
                    }
                );
            },
            edit_comment: function (c) {
                this.view.comment.id = c.id;
                this.view.comment.msg = c.comment
                this.$refs.textarea_comment.focus();
                $vuetify.goTo('comment')
            },
            updateCheck: function (check) {
                this.confirm('Confirmação',
                    'Deseja marcar esta tarefa como finalizada? Essa alteração não poderá ser revertida.',
                    'yellow darken-3', () => {
                        check.status = true;
                        this.store_model(this.models.check, check);
                    });

            },
            searching: function (search) {
                this.search = search;
            },
            filter_show(status) {
                if (this.view.filter_type == 1 && status == 1) return true;
                else if (this.view.filter_type == 0 && status != 1) return true;
                return false;
            },
        },
        mounted() {
            this.list_model(this.models.task);
            this.list_model(this.models.employee);
            this.list_model(this.models.check, {
                your: 1
            });
            this.list_model(this.models.comment);
        }
    };
</script>
@endsection
