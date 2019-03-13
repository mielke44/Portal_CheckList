sources_notifications = {
    data(){
        return{
            models:{
                notification:{
                    routes:{
                        list: routes.notification_list,
                        store: routes.notification_store,
                        destroy: routes.notification_destroy
                    },
                    list:[]
                }
            }
        }
    },
    methods:{
        notif_clear: function () {
            $.ajax({
                url: routes.clrnot,
                method: 'POST',
                datatype: 'json',
                headers: app.headers,
            }).done(response => {
                if (response['error']) app.notify('Ocorreu um erro! Tente Novamente!', 'error');
                app.notify('notificações excluidas!', 'success');
                this.list_model(this.models.notification);
            })
        },
    }
}
