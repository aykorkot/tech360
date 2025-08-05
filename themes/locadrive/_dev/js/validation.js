import $ from 'jquery';
import './lib/jquery.validate.min';

$(document).ready(() => {
  // Custom validation message
  $.extend($.validator.messages, {
    required: 'Ce champ est obligatoire.',
    remote: 'Veuillez corriger ce champ.',
    select: 'veuillez sélectionner une valeur.',
    email: 'Veuillez fournir une adresse électronique valide.',
    url: 'Veuillez fournir une adresse URL valide.',
    date: 'Veuillez fournir une date valide.',
    dateISO: 'Veuillez fournir une date valide (ISO).',
    number: 'Veuillez fournir un numéro valide.',
    digits: 'Veuillez fournir seulement des chiffres.',
    creditcard: 'Veuillez fournir un numéro de carte de crédit valide.',
    equalTo: 'Veuillez fournir encore la même valeur.',
    notEqualTo: 'Veuillez fournir une valeur différente, les valeurs ne doivent pas être identiques.',
    extension: 'Veuillez fournir une valeur avec une extension valide.',
    maxlength: $.validator.format('Veuillez fournir au plus {0} caractères.'),
    minlength: $.validator.format('Veuillez fournir au moins {0} caractères.'),
    rangelength: $.validator.format('Veuillez fournir une valeur qui contient entre {0} et {1} caractères.'),
    range: $.validator.format('Veuillez fournir une valeur entre {0} et {1}.'),
    max: $.validator.format('Veuillez fournir une valeur inférieure ou égale à {0}.'),
    min: $.validator.format('Veuillez fournir une valeur supérieure ou égale à {0}.'),
    step: $.validator.format('Veuillez fournir une valeur multiple de {0}.'),
    maxWords: $.validator.format('Veuillez fournir au plus {0} mots.'),
    minWords: $.validator.format('Veuillez fournir au moins {0} mots.'),
    rangeWords: $.validator.format('Veuillez fournir entre {0} et {1} mots.'),
    letterswithbasicpunc: 'Veuillez fournir seulement des lettres et des signes de ponctuation.',
    alphanumeric: 'Veuillez fournir seulement des lettres, nombres, espaces et soulignages.',
    lettersonly: 'Veuillez fournir seulement des lettres.',
    nowhitespace: "Veuillez ne pas inscrire d'espaces blancs.",
    ziprange: 'Veuillez fournir un code postal entre 902xx-xxxx et 905-xx-xxxx.',
    integer: 'Veuillez fournir un nombre non décimal qui est positif ou négatif.',
    vinUS: "Veuillez fournir un numéro d'identification du véhicule (VIN).",
    dateITA: 'Veuillez fournir une date valide.',
    time: 'Veuillez fournir une heure valide entre 00:00 et 23:59.',
    phoneUS: 'Veuillez fournir un numéro de téléphone valide.',
    phoneUK: 'Veuillez fournir un numéro de téléphone valide.',
    mobileUK: 'Veuillez fournir un numéro de téléphone mobile valide.',
    strippedminlength: $.validator.format('Veuillez fournir au moins {0} caractères.'),
    email2: 'Veuillez fournir une adresse électronique valide.',
    url2: 'Veuillez fournir une adresse URL valide.',
    creditcardtypes: 'Veuillez fournir un numéro de carte de crédit valide.',
    ipv4: 'Veuillez fournir une adresse IP v4 valide.',
    ipv6: 'Veuillez fournir une adresse IP v6 valide.',
    require_from_group: $.validator.format('Veuillez fournir au moins {0} de ces champs.'),
    nifES: 'Veuillez fournir un numéro NIF valide.',
    nieES: 'Veuillez fournir un numéro NIE valide.',
    cifES: 'Veuillez fournir un numéro CIF valide.',
    postalCodeCA: 'Veuillez fournir un code postal valide.',
  });

  $.validator.addMethod('customphone', (value, element) => /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{6,7}?$/.test(value), $.validator.messages.number);

  $.validator.addMethod('customemail', (value, element) => {
    const re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;

    return re.test(value);
  }, $.validator.messages.email);

  // Validation
  $('form').each(function () {
    $(this).validate({
      errorElement: 'div',
      rules: {
        email: {
          customemail: true,
        },
        phone: {
          customphone: true,
        },
        departure_agency: {
          required: true,
        },
        return_agency: {
          required: true,
        },
      },
      messages: {
        departure_agency: {
          required: "Choisir une agence de départ."
        },
        return_agency: {
          required: "Choisir une agence de retour."
        },
        departure_date: {
          required: "Choisir la date et l'heure de départ."
        },
        return_date: {
          required: "Choisir la date et l'heure de retour."
        },
      },
      errorPlacement(error, element) {
        if (element.attr('type') == 'checkbox') {
          error.insertAfter($(element).parents('.custom-checkbox'));
        } else if (element.attr('type') == 'radio') {
          error.insertAfter($(element).parents('.custom-radio'));
        } else {
          error.insertAfter(element);
        }
      },
    });
  });
});
