new Vue({
    el:'#app',
    data:{
        isStop:true,
        blackList:false,
        loading:false,
        status:'',
        listGroupId:[],
        copyListGroupId:[],
        listSuccess:[],
        listFail:[],
        status:'',
        input: {
            cookie: '',
            postId: '',
            content: '',
            groupId:'',
            sleep: 10
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
        allSelected:false,
        cookie:{
            username:'',
            password:''
        },
        data:{
            token:'',
            cookie:''
        }
    },
    methods:{
        async request(route)
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
                        if(this.options.getGroupId == 'all' || this.options.getGroupId == 'custome')
                        {
                            this.defaultValue = {
                                cookie:cookies[key],
                                id:res.data.id,
                                fb_dtsg:res.data.fb_dtsg
                            };
                            await this.getGroupId(cookies[key],res.data.id,res.data.fb_dtsg,route);
                        }
                        else
                        {
                            this.toast('Đang lấy danh sách nhóm do người dùng nhập','warning');
                            await this.getGroupId(cookies[key],res.data.id,res.data.fb_dtsg,route);
                            this.listGroupId = [];
                            this.input.groupId.split("\n").forEach((each) => {
                                this.copyListGroupId.forEach((copy) => {
                                    if(each == copy.id)
                                    {
                                        if(this.blackList && copy.published)
                                        {
                                            return;
                                        }
                                        this.listGroupId.push({
                                            name:copy.name || 'Không tìm thấy nhóm',
                                            id:copy.id || 0,
                                            published:copy.published || false
                                        });
                                    }
                                });
                            });
                            this.toast(`Lấy danh sách thành công ! ${this.listGroupId.length} nhóm sẵn sàng !`,'success');
                            if(this.listGroupId.length > 0)
                            {
                                await this.share(cookies[key],res.data.id,res.data.fb_dtsg,route);
                            }
                            else
                            {
                                this.toast('Không có nhóm nào để thực hiện','error');
                            }
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
        async getGroupId(cookie,id,fb_dtsg,path)
        {
            this.loading = true;
            this.toast('Đang tìm kiếm danh sách nhóm','warning');
            let res = await axios.post('routes/api.php',{
                cookie:cookie,
                fb_dtsg:fb_dtsg,
                route:'get-group-id',
                path:path
            });
            this.toast(res.data.msg,res.data.type);
            if(res.data.status == 200)
            {
                this.listGroupId =  res.data.list_id;
                this.copyListGroupId = this.options.getGroupId == 'custome' ? this.listGroupId : [];

                if(this.options.getGroupId == 'all')
                {
                    var list = [];
                    this.listGroupId.forEach((item) => {
                        if(this.blackList && item.published)
                        {
                            return;
                        }
                        else
                        {
                            list.push(item);
                        }
                    });
                    this.listGroupId = await list;
                    this.toast(`${list.length} nhóm sẵn sàng`,'success');
                    if(list.length > 0)
                    {
                        this.share(cookie,id,fb_dtsg,path);
                    }
                    this.isStop = true;
                    this.toast('Không thể thực hiện do không có nhóm nào','error');
                }
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
            try
            {
                if(this.options.getGroupId == 'custome')
                {
                    this.listGroupId = [];
                    this.customeListGroupId.forEach((item) => {
                        if(this.blackList && item.published)
                        {
                            return;
                        }
                        this.listGroupId.push(item);
                    });
                }
                this.loading = true;
                this.toast(`Đã xác nhận danh sách nhóm ! Chuẩn bị tiến hành`,'info');
                for(let key in this.listGroupId)
                {
                    this.toast(`Còn ${this.listGroupId.length - 1} nhóm nữa`,'info');
                    if(this.isStop)
                    {
                        this.toast('Đã dừng !','success');
                        break;
                    }
                    const messages = this.input.content.split("\n");
                    let randomMsg = messages[Math.floor((Math.random() * messages.length))];
                    await this.sleep(1000 * this.input.sleep);
                    console.log(`%c => ${key}. Tiến hành share bài viết vào nhóm ${this.listGroupId[key].name} ( ${this.listGroupId[key].id} )`,'background: #222; color: #bada55');
                    this.toast(`Đang tiến hành share bài viết vào nhóm ${this.listGroupId[key].name} ( ${this.listGroupId[key].id} )`,'warning');
                    let res = await axios.post('routes/api.php',{
                        cookie:cookie || this.defaultValue.cookie,
                        id:parseInt(id) || parseInt(this.defaultValue.id),
                        fb_dtsg:fb_dtsg || this.defaultValue.fb_dtsg,
                        message:randomMsg,
                        postId:parseInt(this.input.postId),
                        idGroup:parseInt(this.listGroupId[key].id),
                        groupName:this.listGroupId[key].name || 'Không tìm thấy',
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
                    if(key == this.listGroupId.length - 1)
                    {
                        this.isStop = true;
                        swal('','Đã xong','success');
                    }
                    this.listGroupId.shift();
                }
                this.loading = false;
            }
            catch(e)
            {
                this.toast(`Đã có lỗi xảy ra ${e}`,'error');
                this.loading = false;
                if(confirm(`Còn ${this.listGroupId.length} nhóm đang trong hàng chờ ! Bạn có muốn tiếp tục ?`))
                {
                    this.toast('Đã xác nhận ! Xin vui lòng đợi hệ thống khôi phục dữ liệu','success');
                    await this.sleep(2000);
                    this.share(cookie,id,fb_dtsg,route);
                }
            }
        },
        async getCookie()
        {
            try
            {
                this.loading = true;
                this.toast('Đang tải...Xin vui lòng đợi trong giây lát !','info');
                let res = await axios.post('routes/api.php',{
                    username:this.cookie.username,
                    password:this.cookie.password,
                    route:'get-cookie-and-token'
                })
                this.toast(res.data.msg,res.data.type);
                this.data = {
                    cookie:res.data.cookie,
                    token:res.data.token
                }
                this.loading = false;
            }
            catch(e)
            {
                this.toast(e,'error');
                this.loading = false;
            }
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
        toast(text,status)
        {
            this.status = text;
            toastr[status](text);
            toastr.options = {
                "closeButton": true,
                "debug": false,
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