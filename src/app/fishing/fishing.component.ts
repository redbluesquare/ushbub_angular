import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Location } from '@angular/common';

@Component({
  selector: 'app-fishing',
  templateUrl: './fishing.component.html',
  styleUrls: ['./fishing.component.css']
})


export class FishingComponent implements OnInit {

  id:string;
  page:string;
  modalClass:string;
  picname:string;
  picimg:string;
  pics:any;

  constructor(
    private route: ActivatedRoute,
    private location: Location
  ) { }

  COTMpic(a = '',b = ''){
    if(this.modalClass=='modal'){
      if(a!=''){
        this.picname = a;
      }
      if(b!=''){
        this.picimg = this.pics[b];
      }
      this.modalClass = 'modalOpen';
    }else{
      this.modalClass = 'modal';
    }

  }

  ngOnInit() {
    this.modalClass = 'modal';
    this.id = this.route.snapshot.paramMap.get('alias');
    this.page = this.route.snapshot.paramMap.get('category');
    this.pics = {
      'april18':'../../assets/images/201804_phil_cotm_fb.jpg'
    }
  }
}
