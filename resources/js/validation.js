let timer;


// Email validation: check format client-side, then check availability server-side with debounce to avoid excessive requests
document.getElementById('email')?.addEventListener('input', function (e) {
    const email = e.target.value;
    const msg = document.getElementById('email-msg');
    const re = /\S+@\S+\.\S+/;

    if (!msg) {
        return;
    }

    let valid = re.test(email);

    clearTimeout(timer);
 
    timer = setTimeout(async () => {
        try {
            if (valid) {
                const res = await fetch(`/check-email?email=${encodeURIComponent(email)}`);
                const data = await res.json();
                msg.innerText = data.exists ? 'Email already registered.' : 'Email available!';
                msg.className = data.exists ? 'form-error text-sm mb-0' : 'text-accent text-sm mb-0';
            } else {
                msg.innerText = "";
                msg.className = 'form-error text-sm mb-4';
            }
        } catch (err) {
            console.log(err);
            msg.innerText = "";
        }
    }, 500);
});

// Strength bar: 20 points each for length > 8, lowercase alphabet, uppercase alphabet, number, special chars ($@#&!_-)
document.getElementById('setpassword')?.addEventListener('input', (e) => {
    const bar = document.getElementById('strength-fill');
    const barcontainer = document.getElementById('strength-bar');
    const password = e.target.value;
    const passwordMsg = document.getElementById('password-msg');

    if (!bar || !barcontainer) {
        return;
    }

    if (passwordMsg) {
        passwordMsg.innerText = "";
    }

    let strength = 0;
    strength += password.length >= 8 ? 20 : 0;
    if (password.match(/[a-z]+/)) {
        strength += 20;
    }
    if (password.match(/[A-Z]+/)) {
        strength += 20;
    }
    if (password.match(/[0-9]+/)) {
        strength += 20;
    }
    if (password.match(/[$@#&!_-]+/)) {
        strength += 20;
    }

    bar.style.width = strength + '%';
    bar.className = `h-full transition-all ${strength > 75 ? 'bg-green-500' : strength > 33 ? 'bg-orange-500' : 'bg-red-500'} ${strength !== 0 ? 'mt-1' : ''}`;
    barcontainer.className = `h-1 w-full mb-4`;
});


// Reset elements with saved inputs not removable by normal form reset, get rid of error messages, clear strength bar
document.getElementById('reset')?.addEventListener('click', () => {
    const bar = document.getElementById('strength-fill');
    const barcontainer = document.getElementById('strength-bar');
    const form = document.getElementById('reg');
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');

    if (!form || !csrfMeta) {
        return;
    }

    const errormsgs = form.querySelectorAll('.form-error');
    const inputelements = form.querySelectorAll('input');

    fetch('/forget-old', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfMeta.getAttribute('content') || '' }
    }).then(() => {

        inputelements.forEach(element => {
            if (element.name == '_token') return;
            element.setAttribute('value', '');
        });

        errormsgs.forEach(element => {
            if (element.name == '_token') return;
            element.innerText = '';
            element.className = element.id == 'email-msg' ? 'form-error text-sm mb-4' : '';
        });
    });

    if (bar) {
        bar.style.width = 0;
        bar.className = `h-full transition-all bg-red-500`;
    }

    if (barcontainer) {
        barcontainer.className = `h-1 w-full mb-4`;
    }
});
