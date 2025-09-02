@component('mail::message')
# Nuevo mensaje de contacto

**Nombre:** {{ $contact->first_name }} {{ $contact->last_name }}  
**Email:** {{ $contact->email }}  
**Teléfono:** {{ $contact->country_code }} {{ $contact->phone }}  
**Categoría:** {{ $contact->category->name ?? 'N/A' }}  
**Tipo de listado:** {{ ucfirst($contact->tipo_listado) }}  
**Dirección:** {{ $contact->address }}  
**Prefiere WhatsApp:** {{ $contact->whatsapp_contact ? 'Sí' : 'No' }}

---

### Mensaje:
{{ $contact->message }}

@endcomponent

