<template>
    <div class="container">
        <form class="form" @submit.prevent="submit">
            <div class="title">
                <h2>Let’s get started by creating your profile</h2>
                <p>Reach over 65 million homeowners on Homlee</p>
            </div>
            <div class="others">
                <label for="">Business name</label>
                <input type="text" placeholder="Enter your business name here" v-model="dataForm.business_name" required>
            </div>
            <div class="others">
                <label for="">Professional category</label>
                <input type="text" placeholder="Enter your business category" v-model="dataForm.professional_category" required>
            </div>
            <div class="others">
                <label for="">Phone number</label>
                <input type="text" placeholder="For potential clients to reach you" v-model="dataForm.phone_number" required>
            </div>
            <div class="others">
                <label for="">Address</label>
                <input type="text" v-model="dataForm.address" required>
            </div>
            <div class="others">
                <label for="">Contact name</label>
                <input type="text" placeholder="Full name" v-model="dataForm.name" required>
            </div>
            <div class="others">
                <label for="">Business WebSite Address</label>
                <input type="text" placeholder="WWW." v-model="dataForm.business_site" required>
            </div>
            <div class="image">
                <img src="https://img.icons8.com/ios-glyphs/30/undefined/image-file.png"/>
                <label for="businessImage">Image for your business</label>
                <input type="file" @change="getImg" id="businessImage" required>
            </div>
            <div class="others">
                <label for="">Create Your free Account</label>
                <input type="text" placeholder="Enter your e-mail" v-model="dataForm.email" required>
            </div>
            <div class="others">
                <input type="password" placeholder="Password" v-model="dataForm.password" required>   
            </div>
            <div class="check">
                <input type="checkbox">
                <span>
                    <p>Enable text messages(recommended)</p>
                    <span>I agree to receive text messages about new business leads, 
                    product news, and best practices at the number provided pursuant to <a href="">Homlee's Terms of Use</a></span>
                </span>
            </div>
            <div class="others">
                <button>Join Us</button>
            </div>
        </form>
        <div class="pic">
            <h2>{{
                !dataForm.business_name?"Your Business Category":dataForm.business_name
                }}</h2>
            <p>
                {{
                    !dataForm.professional_category?"Your Business Category":dataForm.professional_category
                }}</p>
        </div>
    <Guide v-if="popUp" @popUp="close"/>
    </div>
</template>

<script setup>
import {ref} from "vue"
import { useStore } from "../../store/test.js"
import Guide from "../../components/Popups/GuideComponent.vue"

const popUp = ref(false)
const store = useStore()

const close = ()=>{
    popUp.value = false
}


const dataForm = ref({
    id:"",
    business_name:"",
    professional_category:"",
    phone_number:"",
    address:"",
    business_site:"",
    business_img:"",
    name:"",
    email:"",
    password:""
}) 

const error = ref({
    business_nameLength:"",
    professional_category:"",
    phone_number:"",
    address:"",
    business_site:""
})

const getImg = (e)=>{
    let file = e.target.files[0]
    dataForm.value.business_img = file.name
}


const submit = async()=>{
    if(dataForm.value.business_name.length>0 && dataForm.value.professional_category.length>0 && dataForm.value.phone_number.length>0 && dataForm.value.address.length>0 && dataForm.value.business_site.length>0 && dataForm.value.name.length>0 && dataForm.value.email.length>0 && dataForm.value.password.length>0){
        let res = await fetch("http://homlee.api/professional/addProfessional",{
            method:"POST",
            body:JSON.stringify(dataForm.value)
        })
        let json = await res.json()
        console.log(json);
        dataForm.value.id = json.id
        store.listProfessionals.push(dataForm.value)
        console.log(dataForm.value);
        dataForm.value = ref({
            id:"",
            business_name:"",
            professional_category:"",
            phone_number:"",
            name:"",
            address:"",
            business_site:"",
            business_img:"",
            email:"",
            password:""
        }) 
        popUp.value = true
    }


}

</script>

<style lang='scss' scoped>
$or : #bea100;
.container{
    width: 90%;
    height: max-content;
    display: grid;
    grid-template-columns: repeat(2,1fr);
    column-gap:10px;
    @media screen and (max-width:500px) {
        display: flex;
        justify-content: center;
    }
    .form{
        width: 85%;
        height: max-content;
        display: grid;
        grid-template-rows: repeat(8,1fr);
        margin-top: 58px;
        row-gap: 10px;
        @media screen and (max-width:500px) {
            width: 90%;
            display: block;
        }
        .image{
            width: max-content;
            display: flex;
            align-items: center;
            border: 2px solid rgb(186, 186, 186);
            font-size: 16px;
            color: black;
            width: 80%;
            padding: 7px;
            font-weight: 600;
            img{
                margin-right: 7px;
            }
            @media screen and (max-width:500px) {
                width: 90%;
            }
            label{
                &:hover{
                    cursor: pointer;
                }
            }
            input[type=file] {
                display: none;
            }   
        }
        .others{
            display: flex;
            flex-direction: column;
            font-weight: bold;
            height: max-content;
            @media screen and (max-width:500px) {
                height: 80px;
            }
            .error{
                color: rgba(221, 0, 0, 0.84);
            }
            input{
                width:80%;
                height: 30px;
                padding: 7px;
                border: 2px solid rgb(186, 186, 186);
                font-size: 16px;
                color: black;
                @media screen and (max-width:500px) {
                    width: 90%;
                }
            }
            input:focus{
                outline: none;  
            }
            button{
                width: 150px;
                height: 40px;
                font-size: 18px;
                color: white;
                background-color: rgba(0, 0, 0, 0.908);
                border: none;
                border-radius: 3px;
                &:hover{
                    cursor: pointer;
                    background-color: rgba(0, 0, 0, 0.849);
                }
            }
        }
        
        .check{
            display: flex;
            flex-direction: row;
            input{
                width: max-content;
                height: max-content;
                padding: 0px;
                margin-top: 4px;
                margin-right: 4px;
            }
            span{
                font-size: 13px;
                font-weight: normal;
                span{
                    color: gray;
                }
            }
        
        }
    }
    .pic{
        height: 93%;
        margin-top: 55px;
        background-image: url('/assets/profile-preview-bg@2x.png');
        background-size: cover;
        background-position: center;
        position: relative;
        h2{
            position: absolute;
            top: 273px;
            left: 147px;
        }
        p{
            position: absolute;
            top:  300px;
            left: 150px;
        }
        @media screen and (max-width:500px) {
            display: none;
        }
    }
}

</style>