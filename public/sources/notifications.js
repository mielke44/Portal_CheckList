sources_notifications = {
    data(){
        return{
            notifications:[]
        }
    },
    methods:{
        list_notifications: function () {
            $.ajax({
                url: "{{route('getnoti')}}",
                method: 'GET',
                dataType: "json",
            }).done(response => {
                this.notifications = response;
            });
        },
        clearnot: function () {
            $.ajax({
                url: "{{route('clrnot')}}",
                method: 'POST',
                datatype: 'json',
                headers: app.headers,
            }).done(response => {
                if (response['error']) app.notify('Ocorreu um erro! Tente Novamente!', 'error');
                app.notify('notificações excluidas!', 'success');
                this.list_notifications();
            })
        },
        get_not_source: function (id) {
            $.ajax({
                url: "{{route('updnot')}}",
                method: 'POST',
                dataType: "json",
                headers: app.headers,
                data: {
                    id: id
                },
            }).done(response => {
                this.list_notifications();
                window.location = '{{route("emp.yourchecklist.view")}}';
            });
        },
        update_notification: function () {
            $.ajax({
                url: "{{route('getflagnoti')}}",
                method: 'GET',
                dataType: "json",
            }).done(response => {
                if (JSON.stringify(response) == "true") {
                    this.list_notifications()
                };
            });
        },
    }
}
