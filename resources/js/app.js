import './bootstrap'
import 'notyf/notyf.min.css'
import { Notyf } from 'notyf'

window.notyf = new Notyf({
  duration: 2500,
  dismissible: true,
  position: { x: 'right', y: 'top' },

  types: [
    {
      type: 'info',
      background: '#10B981', // emerald-500
      textColor: '#ffffff',
      icon: false,
    },
    {
      type: 'success',
      background: '#10B981',
      textColor: '#ffffff',
      icon: false,
    },
    {
      type: 'warning',
      background: '#F59E0B',
      textColor: '#111827',
      icon: false,
    },
    {
      type: 'error',
      background: '#EF4444',
      textColor: '#ffffff',
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
