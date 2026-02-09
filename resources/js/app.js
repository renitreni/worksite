import './bootstrap'
import 'notyf/notyf.min.css'
import { Notyf } from 'notyf'

window.notyf = new Notyf({
  duration: 2200,
  position: { x: 'right', y: 'top' },
  dismissible: true,
  ripple: false,
  types: [
    {
      type: 'success',
      background: '#059669', 
      icon: false,
    },
    {
      type: 'info',
      background: '#0f766e', 
      icon: false,
    },
    {
      type: 'warning',
      background: '#d97706',
      icon: false,
    },
    {
      type: 'error',
      background: '#e11d48', 
      icon: false,
    },
  ],
})




window.notify = (type = 'success', message = '', title = '') => {
  const text = title ? `${title}: ${message}` : message
  if (type === 'error') return notyf.error(text)
  if (type === 'warning') return notyf.open({ type: 'warning', message: text })
  return notyf.success(text)
}

// Optional: auto-bind buttons/links using data-toast attributes
document.addEventListener('click', (e) => {
  const el = e.target.closest('[data-toast]')
  if (!el) return

  const msg = el.getAttribute('data-toast') || ''
  const type = el.getAttribute('data-toast-type') || 'success'
  const title = el.getAttribute('data-toast-title') || ''
  window.notify(type, msg, title)
})
