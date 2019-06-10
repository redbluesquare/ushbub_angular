import { Component, OnInit } from '@angular/core';
import { ApiDataService } from '../api-data.service';

@Component({
  selector: 'app-event-list',
  templateUrl: './event-list.component.html',
  styleUrls: ['./event-list.component.css']
})
export class EventListComponent implements OnInit {

  constructor(private apiDataService: ApiDataService) { }
  modalClass:string = 'modal';
  first_name:string;
  email:string;
  data:any;

  closeModal(){
    if(this.modalClass=='modal'){
      this.modalClass = 'modalOpen';
      this.first_name = '';
      this.email = '';
    }else{
      this.modalClass = 'modal';
    }
  }

  closeForm(){
    this.modalClass = 'modal';
    this.first_name = '';
    this.email = '';
    alert("Thanks, we sent you a mail. Open it now to get your next challange!")
  }

  subscribe(){
    this.data = {
      first_name:this.first_name,
      email:this.email,
      sub_point:"blog - small shops failing"
    }
    this.apiDataService.saveUserSubscribe(this.data)
    .subscribe(vendors => this.closeForm());
  }

  ngOnInit() {
    this.modalClass = 'modal';
  }

}
