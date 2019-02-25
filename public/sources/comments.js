sources_comments = {
    data(){
        return{
            comments:[]
        }
    },
    methods:{
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
    }
}
