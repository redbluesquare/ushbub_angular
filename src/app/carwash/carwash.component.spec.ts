import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CarwashComponent } from './carwash.component';

describe('CarwashComponent', () => {
  let component: CarwashComponent;
  let fixture: ComponentFixture<CarwashComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CarwashComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CarwashComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
