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
 * Global helper:
 * toast('success', 'Saved!')
 * toast('error', 'Something went wrong')
 */
window.toast = (type = 'info', message = '') => {
  if (!window.notyf) return

  if (type === 'success') return window.notyf.success(message)
  if (type === 'error') return window.notyf.error(message)

  return window.notyf.open({ type, message })
}

/**
 * Optional: HTML click toast
 * <button data-toast="Saved!" data-toast-type="success"></button>
 */
document.addEventListener('click', (e) => {
  const el = e.target.closest('[data-toast]')
  if (!el) return

  const msg = el.getAttribute('data-toast') || ''
  const type = el.getAttribute('data-toast-type') || 'success'

  window.toast(type, msg)
})
