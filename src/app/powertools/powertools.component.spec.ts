import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PowertoolsComponent } from './powertools.component';

describe('PowertoolsComponent', () => {
  let component: PowertoolsComponent;
  let fixture: ComponentFixture<PowertoolsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PowertoolsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PowertoolsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
