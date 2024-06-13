import { ComponentFixture, TestBed } from '@angular/core/testing';

import { IngresarPatologiaComponent } from './ingresar-patologia.component';

describe('IngresarPatologiaComponent', () => {
  let component: IngresarPatologiaComponent;
  let fixture: ComponentFixture<IngresarPatologiaComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [IngresarPatologiaComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(IngresarPatologiaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
