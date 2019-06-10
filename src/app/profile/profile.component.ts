import { Component, OnInit } from '@angular/core';
import { User } from '../user';
import { ApiDataService } from '../api-data.service';
import { Location } from '@angular/common';
import { ShopComponent } from '../shop/shop.component';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.css']
})
export class ProfileComponent implements OnInit {

  constructor(private apiDataService:ApiDataService) { }
  user:User;
  first_name:string;
  last_name:string;
  company:string;
  profession:string;
  aboutme:string;
  modalClass:string;
  selectedFile:File;
  myshops:any;
  showImageForm:boolean;
  showProfileForm:boolean;
  showProfile:boolean;
  formdata:any;
  apptoken:string;

  loadUser(user){
    this.user = user;
  }

  editimage(){
    //TODO: Load image form to add new image
    this.showImageForm=true;
    this.showProfile=false;
  }

  editProfile(){
    this.showProfileForm=true;
    this.showProfile=false;
    this.first_name = this.user.first_name;
    this.last_name = this.user.last_name;
    this.profession = this.user.profession.value;
    this.company = this.user.company.value;
    this.aboutme = this.user.aboutme.value;
  }

  editShop(alias){

  }

  onFileSelected(event){
    this.selectedFile = event.target.files[0];
  }

  saveUser(){
    this.apiDataService.saveUser(this.first_name,this.last_name,this.profession,this.company,this.aboutme).subscribe(result => {
      console.log(result);
      this.showProfileForm=false;
      this.showProfile=true;
      this.apiDataService.getProfiles().subscribe(user => this.loadUser(user));
    });
  }

  uploadPhoto(l,id){
    this.apiDataService.saveUserImage(l,id,this.selectedFile).subscribe(result => {
      console.log(result);
      this.showImageForm=false;
      this.showProfileForm=false;
      this.showProfile=true;
      this.apiDataService.getProfiles()
      .subscribe(user => this.loadUser(user));
    });
  }


  ngOnInit() {
    this.apiDataService.getProfiles()
    .subscribe(user => this.loadUser(user));
    this.modalClass = 'modal';
    this.showImageForm=false;
    this.showProfile=true;

    this.apiDataService.getVendors(null,1)
    .subscribe(vendors => this.myshops = vendors);
  }

}
