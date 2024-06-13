import { ComponentFixture, TestBed } from '@angular/core/testing';

import { IngresarHojaPreComponent } from './ingresar-hoja-pre.component';

describe('IngresarHojaPreComponent', () => {
  let component: IngresarHojaPreComponent;
  let fixture: ComponentFixture<IngresarHojaPreComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [IngresarHojaPreComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(IngresarHojaPreComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
