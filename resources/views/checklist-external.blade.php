@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Lista de tarefas')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap>
        <v-flex xs12>
            <v-expansion-panel v-model='model_checks' readonly>
                <v-expansion-panel-content v-for='(c,i) in checks'>
                    <div slot="header">
                        <v-layout row wrap fill-height align-center>
                            <v-flex xs1>
                                <v-checkbox v-model="c.status" @change='updateCheck(c.id,c.status)'></v-checkbox>
                            </v-flex>
                            <v-flex xs11 @click='model_checks=model_checks==i?-1:i'>
                                @{{tasks.find(t=>t.id==c.task_id).name}}
                            </v-flex>
                        </v-layout>
                    </div>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-flex xs6>
                                <p class='font-weight-bold'>Descrição</p>
                                @{{tasks.find(t=>t.id==c.task_id).description}}
                            </v-flex>
                            <v-flex xs6>

                                <v-layout row wrap>
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
                <v-expansion-panel-content v-if='checks.length==0'>
                    <div slot="header">Nenhuma tarefa está desiginada para você</div>
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
<script>
    Vue.component("page", {
        props: {
            screen: String
        },
        data() {
            return {
                model_checks: null,
                tasks: [],
                checklists: [],
                checks: [],
                dialog_comment: false,
                comments: [],
                form: {
                    comment: '',
                    comment_id: '',
                }

            }
        },
        watch: {
            model_checks: function (val) {
                this.list_comment(this.checks[val].id);
            },
        },
        methods: {
            store: function () {
                if (this.$refs.form.validate()) {
                    $.ajax({
                        url: "{{route('profile.store')}}",
                        method: "POST",
                        dataType: "json",
                        headers: app.headers,
                        data: this.form,
                        success: (response) => {
                            this.list();
                            this.form_view = false;
                            if (this.form.id == "") app.notify("Perfil criado com sucesso!",
                                "success");
                            else app.notify("Edição salva", "success");
                        }
                    });
                }
            },
            list: function () {
                $.ajax({
                    url: "{{route('emp.yourchecklist')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.checks = response;

                });
            },
            list_tasks: function () {
                $.ajax({
                    url: "{{route('task.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.tasks = response;
                    this.list();
                });
            },
            list_comment: function (id) {
                this.comments = [];
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
            store_comment: function () {
                $.ajax({
                    url: "{{route('comment.store')}}",
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
                            url: "{{route('comment.remove')}}",
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
            updateCheck: function (check_id, status) {
                form_data = {
                    check_id: check_id
                };
                form_data.status = status ? 1 : 0;

                $.ajax({
                    url: "{{route('check.edit')}}",
                    method: "POST",
                    dataType: "json",
                    headers: app.headers,
                    data: form_data
                }).done(response => {
                    app.notify("Tarefa modificada!", "success");
                });
            },
        },
        mounted() {
            this.list_tasks();

        }
    });
</script>
@endsection
