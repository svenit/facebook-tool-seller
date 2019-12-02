new Vue({
    el:'#app',
    data:{
        loading:false,
        status:'',
        listGroupId:[],
        listSuccess:[],
        listFail:[],
        status:'',
        input: {
            cookie: '',
            postId: '',
            content: '',
            groupId:'',
            sleep: 15
        },
        options:{
            autoGetGroupId:true
        }
    },
    methods:{
        async request()
        {
            
            if(this.input.cookie.trim() && this.input.postId.trim())
            {
                this.listGroupId = [];
                const cookies = this.input.cookie.split("\n");
                this.toast('Đang kiểm tra cookie','warning');
                this.loading = true;
                for(let key in cookies)
                {
                    let res = await axios.post('routes/api.php',{
                        cookie:cookies[key],
                        route:'check-cookie'
                    });
                    this.toast(res.data.msg,res.data.type);
                    if(res.data.status == 200)
                    {
                        if(this.options.autoGetGroupId)
                        {
                            await this.getGroupId(cookies[key],res.data.id,res.data.fb_dtsg);
                        }
                        else
                        {
                            this.toast('Đang lấy danh sách nhóm do người dùng nhập','warning');
                            const groupId = this.input.groupId.split("\n");
                            await this.listGroupId.push({
                                id:groupId[0],
                                name:'Unknown'
                            });
                            this.toast('Lấy danh sách thành công','success');
                            await this.share(cookies[key],res.data.id,res.data.fb_dtsg);
                        }
                    }
                }
                this.loading = false;
            }
            else
            {
                swal('','Bạn chưa nhập đầy đủ thông tin','info');
            }
        },
        async getGroupId(cookie,id,fb_dtsg)
        {
            this.loading = true;
            this.toast('Đang tìm kiếm danh sách nhóm','warning');
            let res = await axios.post('routes/api.php',{
                cookie:cookie,
                fb_dtsg:fb_dtsg,
                route:'get-group-id'
            });
            this.toast(res.data.msg,res.data.type);
            if(res.data.status == 200)
            {
                this.listGroupId = res.data.list_id;
                this.share(cookie,id,fb_dtsg);
            }
            this.loading = false;
        },
        sleep(ms)
        {
            this.loading = true;
            this.toast(`${ms/1000}s sau sẽ thực hiện tiến trình`,'info');
            return new Promise(resolve => setTimeout(resolve, ms));
        },
        async share(cookie,id,fb_dtsg)
        {
            this.loading = true;
            this.toast('Chuẩn bị tiến hành share bài viết','warning');
            const messages = this.input.content.split("\n");
            let randomMsg = messages[Math.floor((Math.random() * messages.length))];
            for(let key in this.listGroupId)
            {
                await this.sleep(1000 * this.input.sleep);
                console.log(`%c => ${key}. Tiến hành share bài viết vào nhóm ${this.listGroupId[key].name} ( ${this.listGroupId[key].id} )`,'background: #222; color: #bada55');
                this.toast(`Đang tiến hành share bài viết vào nhóm ${this.listGroupId[key].name} ( ${this.listGroupId[key].id} )`,'warning');
                let res = await axios.post('routes/api.php',{
                    cookie:cookie,
                    id:parseInt(id),
                    fb_dtsg:fb_dtsg,
                    message:randomMsg,
                    postId:parseInt(this.input.postId),
                    idGroup:parseInt(this.listGroupId[key].id),
                    groupName:this.listGroupId[key].name,
                    route:'share-live-stream'
                });
                this.toast(res.data.msg,res.data.type);
                if(res.data.status == 200)
                {
                    this.listSuccess.push(res.data);
                    $('.data-list').animate({scrollTop: document.body.scrollHeight},'fast');
                }
                else
                {
                    this.listFail.push(res.data);
                    $('.data-list').animate({scrollTop: document.body.scrollHeight},'fast');
                }
            }
            this.loading = false;
        },
        toast(text,status)
        {
            this.status = text;
            toastr[status](text);
            toastr.options = {
                "closeButton": true,
                "debug": true,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }
    }
});