import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BtAccountComponent } from './bt-account.component';

describe('BtAccountComponent', () => {
  let component: BtAccountComponent;
  let fixture: ComponentFixture<BtAccountComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BtAccountComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BtAccountComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
