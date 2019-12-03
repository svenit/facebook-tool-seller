new Vue({
    el:'#app',
    data:{
        loading:false,
        status:'',
        listGroupId:[],
        copyListGroupId:[],
        listSuccess:[],
        listFail:[],
        status:'',
        input: {
            cookie: '',
            money: 0,
            content: '',
            groupId:'',
            sleep: 5
        },
        options:{
            getGroupId:'all'
        },
        customeListGroupId:[],
        paginate:10,
        current:1,
        defaultValue:{
            cookie:'',
            fb_dtsg:'',
            id:''
        },
        images:[],
        allSelected:false
    },
    methods:{
        async request()
        {
            
            if(this.input.cookie.trim())
            {
                this.listGroupId = [];
                const cookies = this.input.cookie;
                this.toast('Đang kiểm tra cookie','warning');
                this.loading = true;
                let res = await axios.post('routes/api.php',{
                    cookie:cookies,
                    route:'check-cookie'
                });
                this.toast(res.data.msg,res.data.type);
                if(res.data.status == 200)
                {
                    this.defaultValue = {
                        cookie:cookies,
                        id:res.data.id,
                        fb_dtsg:res.data.fb_dtsg
                    };
                    this.getGroupId(cookies,res.data.id,res.data.fb_dtsg,'post-group')
                }
                this.loading = false;
            }
            else
            {
                swal('','Bạn chưa nhập đầy đủ thông tin','info');
            }
        },
        async getGroupId(cookie,id,fb_dtsg,route)
        {
            this.loading = true;
            this.toast('Đang tìm kiếm danh sách nhóm','warning');
            let res = await axios.post('routes/api.php',{
                cookie:cookie || this.defaultValue.cookie,
                fb_dtsg:fb_dtsg || this.defaultValue.fb_dtsg,
                route:'get-group-id'
            });
            this.toast(res.data.msg,res.data.type);
            if(res.data.status == 200)
            {
                this.listGroupId = this.copyListGroupId = res.data.list_id;
            }
            this.loading = false;
        },
        sleep(ms)
        {
            this.loading = true;
            this.toast(`${ms/1000}s sau sẽ thực hiện tiến trình`,'info');
            return new Promise(resolve => setTimeout(resolve, ms));
        },
        async share(cookie,id,fb_dtsg,route)
        {
            if(this.options.getGroupId == 'custome')
            {
                this.listGroupId = this.customeListGroupId;
            }
            else if(this.options.getGroupId == 'list')
            {
                this.input.groupId.split("\n").forEach((each) => {
                    this.copyListGroupId.forEach((copy) => {
                        if(each == copy.id)
                        {
                            this.listGroupId.push({
                                name:each,
                                id:each,
                                published:false
                            });
                        }
                    });
                });
            }
            this.loading = true;
            this.toast('Chuẩn bị tiến hành đăng bài viết','warning');
            if(this.listGroupId.length > 0)
            {
                for(let key in this.listGroupId)
                {
                    await this.uploadImage(document.getElementById('file'));
                    const messages = this.input.content.split("\n");
                    let randomMsg = messages[Math.floor((Math.random() * messages.length))];
                    await this.sleep(1000 * this.input.sleep);
                    console.log(`%c => ${key}. Tiến hành đăng bài viết vào nhóm ${this.listGroupId[key].name} ( ${this.listGroupId[key].id} )`,'background: #222; color: #bada55');
                    this.toast(`Đang tiến hành đăng bài viết vào nhóm ${this.listGroupId[key].name} ( ${this.listGroupId[key].id} )`,'warning');
                    let res = await axios.post('routes/api.php',{
                        cookie:cookie || this.defaultValue.cookie,
                        id:parseInt(id) || parseInt(this.defaultValue.id),
                        fb_dtsg:fb_dtsg || this.defaultValue.fb_dtsg,
                        message:randomMsg,
                        money:parseInt(this.input.money),
                        idGroup:parseInt(this.listGroupId[key].id),
                        groupName:this.listGroupId[key].name,
                        attach:this.images,
                        route:route
                    });
                    this.toast(res.data.msg,res.data.type);
                    if(res.data.status == 200)
                    {
                        this.listSuccess.push(res.data);
                        this.listGroupId[key].published = true;
                        $('.data-list').animate({scrollTop: document.body.scrollHeight},'fast');
                    }
                    else
                    {
                        this.listFail.push(res.data);
                        $('.data-list').animate({scrollTop: document.body.scrollHeight},'fast');
                    }
                    this.images = [];
                }
            }
            else
            {
                this.toast('Không tìm thấy nhóm nào','error');
            }
            this.loading = false;
        },
        async uploadImage(e)
        {
            for(var i = 0; i < e.files.length;i++)
            {
                console.log(e.files);
                this.loading = true;
                this.toast('Đang tải ảnh lên','info');
                var form = new FormData();
                form.append('farr',e.files[i]);
                form.append('cookie',this.defaultValue.cookie);
                form.append('fb_dtsg',this.defaultValue.fb_dtsg);
                form.append('id',this.defaultValue.id);
                form.append('route','upload-image');
                let res = await axios.post('routes/api.php?route=upload-image',form);
                this.toast(res.data.msg,res.data.type);
                if(res.data.status == 200)
                {
                    this.images.push({
                        id:res.data.photo_id,
                        url:res.data.url
                    });
                }
                this.loading = false;
            };
        },
        selectAll()
        {
            this.customeListGroupId = [];
            if(!this.allSelected)
            {
                this.copyListGroupId.forEach((each) => {
                    this.customeListGroupId.push({
                        id:each.id,
                        name:each.name
                    });
                });
            }
        },
        removeImage(key)
        {
            this.images.splice(key,1);
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
        },
        gotoPage(n)
        {
            this.current = n;
            this.listGroupId = this.copyListGroupId;
            this.listGroupId = this.listGroupId.slice((n * this.paginate) - this.paginate,n * this.paginate);
        },
        searchGroup(e)
        {
            this.listGroupId = this.copyListGroupId.filter((filter) => {
                if(filter.name.toLowerCase().indexOf(e.target.value.toLowerCase()) >= 0)
                {
                    return filter;
                }
            });
        }
    }
});