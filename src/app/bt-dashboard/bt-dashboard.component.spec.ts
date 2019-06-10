import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BtDashboardComponent } from './bt-dashboard.component';

describe('BtDashboardComponent', () => {
  let component: BtDashboardComponent;
  let fixture: ComponentFixture<BtDashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BtDashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BtDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
