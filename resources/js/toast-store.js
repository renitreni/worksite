export function registerToastStore(Alpine) {
  Alpine.store('toast', {
    items: [],

    show(type = 'info', message = '', title = '', ms = 3000) {
      const id = Date.now() + Math.random();
      const t = {
        id,
        type,
        title: title || (type === 'success' ? 'Success'
                    : type === 'error' ? 'Error'
                    : type === 'warning' ? 'Warning' : 'Info'),
        message,
        ms,
        progress: 100,
        _timer: null,
        _tick: null,
        _remaining: ms,
        _startedAt: Date.now(),
      };

      this.items = [t, ...this.items].slice(0, 4);
      this._start(t.id);
    },

    remove(id) {
      const t = this.items.find(x => x.id === id);
      if (t?._timer) clearTimeout(t._timer);
      if (t?._tick) clearInterval(t._tick);
      this.items = this.items.filter(x => x.id !== id);
    },

    pause(id){
      const t = this.items.find(x => x.id === id);
      if(!t) return;
      if (t._timer) clearTimeout(t._timer);
      if (t._tick) clearInterval(t._tick);

      const elapsed = Date.now() - t._startedAt;
      t._remaining = Math.max(0, t._remaining - elapsed);
    },

    resume(id){
      const t = this.items.find(x => x.id === id);
      if(!t) return;
      this._start(id);
    },

    _start(id){
      const t = this.items.find(x => x.id === id);
      if(!t) return;

      t._startedAt = Date.now();
      t._timer = setTimeout(() => this.remove(id), t._remaining);

      if (t._tick) clearInterval(t._tick);
      t._tick = setInterval(() => {
        const elapsed = Date.now() - t._startedAt;
        const left = Math.max(0, t._remaining - elapsed);
        t.progress = t._remaining ? Math.round((left / t._remaining) * 100) : 0;
      }, 100);
    }
  });

  // optional: global helper usable anywhere
  window.toast = (type, msg, title = '', ms = 3000) =>
    Alpine.store('toast').show(type, msg, title, ms);
}
