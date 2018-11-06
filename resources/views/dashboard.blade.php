@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Dashboard')


@section('l-content')


<v-container grid-list-lg style='height:300px'>
    <v-layout row wrap fill-height>
        <v-flex xs4>
            <v-card height="100%" color='orange' dark>
                asfas
            </v-card>
        </v-flex>
        <v-flex xs4>
            <v-card height="100%" color='blue' dark>
                asfas
            </v-card>
        </v-flex>
        <v-flex xs4>
            <v-card height="100%" color='purple' dark>
                asfas
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
