@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Dashboard')


@section('l-content')


<v-container grid-list-lg style='height:300px'>
    <v-layout row wrap fill-height>
        <v-flex xs4>
            <v-card height="100%" color='orange' dark>
                <v-container grid-list-xs fill-height>
                    <v-layout row wrap fill-height>
                        <v-flex xs12 class='headline text-xs-center'>
                            Empregados
                        </v-flex>
                        <v-flex xs12>
                            <v-container grid-list-xs fill-height>
                                <div style='width:100%' class='text-xs-center'>
                                    <v-progress-circular :value="70" size='100' width='15'></v-progress-circular>
                                </div>
                            </v-container>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-card-title primary-title>

                </v-card-title>
            </v-card>
        </v-flex>
        <v-flex xs4>
            <v-card height="100%" color='blue' dark>
                <v-container grid-list-xs fill-height>
                    <v-layout row wrap fill-height>
                        <v-flex xs12 class='headline text-xs-center'>
                            Lista de tarefas
                        </v-flex>
                        <v-flex xs12>
                            <v-container grid-list-xs fill-height>
                                <div style='width:100%' class='text-xs-center'>
                                    <v-progress-circular :value="20" size='100' width='15'></v-progress-circular>
                                </div>
                            </v-container>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card>
        </v-flex>
        <v-flex xs4>
            <v-card height="100%" color='purple' dark>
                <v-container grid-list-xs fill-height>
                    <v-layout row wrap fill-height>
                        <v-flex xs12 class='headline text-xs-center'>
                            Tarefas
                        </v-flex>
                        <v-flex xs12>
                            <v-container grid-list-xs fill-height>
                                <div style='width:100%' class='text-xs-center'>
                                    <v-progress-circular :value="50" size='100' width='15'></v-progress-circular>
                                </div>
                            </v-container>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card>
        </v-flex>
    </v-layout>
</v-container>


@endsection

@section('l-js')
<script>
    Vue.component("page", {
        data() {
            return {
                test: "chris"
            }
        }

    });
</script>
@endsection
