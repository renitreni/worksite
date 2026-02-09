// resources/js/app.js
import './bootstrap'

import 'notyf/notyf.min.css'
import { Notyf } from 'notyf'

// Create ONE Notyf instance for the whole app
window.notyf = new Notyf({
  duration: 2200,
  position: { x: 'right', y: 'top' },
  dismissible: true,
  ripple: false,
  types: [
    { type: 'success', background: '#059669', icon: false },
    { type: 'info',    background: '#0f766e', icon: false },
    { type: 'warning', background: '#d97706', icon: false },
    { type: 'error',   background: '#e11d48', icon: false },
  ],
})

/**
 * Global helper you can call anywhere:
 * toast('success', 'Saved!')
 * toast('error', 'Something went wrong')
 */
window.toast = (type = 'info', message = '') => {
  const t = String(type || 'info')
  const msg = String(message || '')

  if (!window.notyf) return

  if (t === 'success') return window.notyf.success(msg)
  if (t === 'error') return window.notyf.error(msg)

  // for info/warning/custom types
  return window.notyf.open({ type: t, message: msg })
}

/**
 * OPTIONAL: click-to-toast using HTML attributes:
 * <button data-toast="Saved!" data-toast-type="success">Test</button>
 */
document.addEventListener('click', (e) => {
  const el = e.target.closest('[data-toast]')
  if (!el) return

  const msg = el.getAttribute('data-toast') || ''
  const type = el.getAttribute('data-toast-type') || 'success'
  window.toast(type, msg)

window.toast = (type = 'success', message = '', title = '') => {
  const text = title ? `${title}: ${message}` : message

  if (type === 'error') return notyf.error(text)
  if (type === 'warning') return notyf.open({ type: 'warning', message: text })
  if (type === 'info') return notyf.open({ type: 'info', message: text })

  return notyf.success(text)
}

})
