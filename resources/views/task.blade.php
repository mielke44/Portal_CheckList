@extends('layouts.default.index')

@section('title','Portal Checklist')
@section('l-title','Tarefas')


@section('l-content')


<v-container>
    <v-layout row wrap>
        <v-flex class='text-xs-right'>
            <v-btn color="primary">Adicionar empregado</v-btn>
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
