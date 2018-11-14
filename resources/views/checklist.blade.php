@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Lista de tarefas')


@section('l-content')


<v-container grid-list-lg>
    <!-- LISTA -->
    <v-layout row wrap v-if="!form_view">
        <v-flex class='text-xs-right'>
            <v-btn color="primary" @click="add()">Adicionar Lista de Tarefas</v-btn>
        </v-flex>
        <v-flex xs12>
            <v-expansion-panel>
                <v-expansion-panel-content v-for='l in clists'>
                    <div slot="header">
                        <v-layout row wrap fill-height align-center>
                            <v-flex xs6 class='font-weight-bold'>
                                @{{l.name}}
                            </v-flex>
                        </v-layout>
                    </div>
                    <v-container grid-list-xs>
                        <v-layout row wrap>
                            <v-flex xs3 class='font-weight-bold'>
                                Perfil:
                            </v-flex>
                            <v-flex>
                                @{{l.profile.name}}
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs3 class='font-weight-bold' v-if="l.dependences.length>0">
                                Tarefas:
                            </v-flex>
                            <v-flex xs9>
                                <v-layout row wrap>
                                    <template v-for="d in l.dependences">
                                        <v-flex xs6>
                                            @{{d.name}}
                                        </v-flex>
                                        <v-flex xs6 class='caption'>
                                            @{{d.desc}}
                                        </v-flex>
                                    </template>
                                </v-layout>
                            </v-flex>
                            <v-flex xs12 class='text-xs-right'>
                                <v-btn @click="edit(l.id)" color="yellow darken-2" outline>
                                    <v-icon dark class='mr-2'>edit</v-icon> Editar
                                </v-btn>
                                <v-btn @click="destroy(l.id)" color="red" outline>
                                    <v-icon dark class='mr-2'>delete</v-icon> Remover
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-expansion-panel-content>
            </v-expansion-panel>
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
                            <v-select v-model="form.profile_id" :items="profile" item-text="name" item-value="id" label="Perfil"
                            :rules="rules.prof_id" persistent-hint required></v-select>
                            <v-autocomplete
                                v-model="form.dependences"
                                :items="task"
                                label="Tarefas"
                                item-text="name"
                                item-value="id"
                                multiple
                            >
                                <template
                                    slot="selection"
                                    slot-scope="data"
                                >
                                    <v-chip
                                        :selected="data.selected"
                                        close class="chip--select-multi"
                                        @input="remove(data.item)">@{{data.item.name}}</v-chip>
                                </template>
                                <template
                                    slot="item"
                                    slot-scope="data"
                                >
                                    <template>
                                        <v-list-tile-content>
                                            <v-list-tile-title v-html="data.item.name"></v-list-tile-title>
                                            <v-list-tile-sub-title v-html="data.item.description"></v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </template>
                                </template>
                            </v-autocomplete>
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
                clists: [
                ],
                profile:[
                ],
                task:[
                ],
                form_view: false,
                form_texts: {
                    title: "",
                    button: ""
                },
                rules: {
                    name: [
                        v => !!v || 'Campo obrigtório',
                        v => (v && v.length <= 25) || 'Máximo 25 caracteres'
                    ],
                    dep: [
                        v => !!v || 'Campo obrigtório'
                    ],
                    prof_id: [
                        v => !!v || 'Campo obrigtório'
                    ],
                },
                form: {
                    id: "",
                    name: '',
                    profile_id:'',
                    dependences: ''
                },
            }
        },
        methods: {
            add: function () {
                this.form_view = true;
                this.form_texts.title = "Criar lista de tarefas";
                this.form_texts.button = "Criar";
                this.form = {
                    id: "",
                    name: '',
                    profile_id: '',
                    dependences: ''
                }
            },
            store: function () {
                if (this.$refs.form.validate()) {
                    $.ajax({
                        url: "{{route('checklist.store')}}",
                        method: "POST",
                        dataType: "json",
                        headers: app.headers,
                        data: this.form,
                        success: (response) => {
                            this.list();
                            this.form_view = false;
                            if(this.form.id=="")app.notify("Lista de tarefa criada com sucesso!","success");
                            else app.notify("Edição salva","success");
                        }
                    });
                }
            },
            list: function () {
                $.ajax({
                    url: "{{route('checklist.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.clists = response['clists'];
                });
            },
            list_profile: function () {
                $.ajax({
                    url: "{{route('profile.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.profile = response;
                });
            },
            list_task: function () {
                $.ajax({
                    url: "{{route('task.list')}}",
                    method: "GET",
                    dataType: "json",
                }).done(response => {
                    this.task = response;
                });
            },
            edit: function (id) {
                $.ajax({
                    url: "{{route('checklist.edit')}}",
                    method: "GET",
                    dataType: "json",
                    data: {
                        id: id
                    },
                }).done(response => {
                    this.form_texts.title = "Editar Lista";
                    this.form_texts.button = "Salvar";
                    this.form = response;
                    this.form_view = true;
                });
            },
            destroy: function (id) {
                $.ajax({
                    url: "{{route('checklist.destroy')}}",
                    method: "DELETE",
                    dataType: "json",
                    headers: app.headers,
                    data: {
                        id: id
                    },
                    success: (response) => {
                        this.list();
                        app.notify("Lista de tarefa removida","error");
                    }
                });
            },
            remove (item) {
                const index = this.friends.indexOf(item.name)
                if (index >= 0) this.friends.splice(index, 1)
            }
        },
        watch: {
            isUpdating (val) {
                if (val) {
                    setTimeout(() => (this.isUpdating = false), 3000)
                }
            }
        },
        mounted() {
            this.list();
            this.list_task();
            this.list_profile();
            setTimeout(()=>{app.screen = 3},1);
        }
    });
</script>
@endsection
