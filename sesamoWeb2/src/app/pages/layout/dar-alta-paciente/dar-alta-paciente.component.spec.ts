import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DarAltaPacienteComponent } from './dar-alta-paciente.component';

describe('DarAltaPacienteComponent', () => {
  let component: DarAltaPacienteComponent;
  let fixture: ComponentFixture<DarAltaPacienteComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [DarAltaPacienteComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(DarAltaPacienteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
