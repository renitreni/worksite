@extends('adminpage.layout')
@section('title','Categories / Skills / Locations')
@section('page_title','Manage Categories, Skills, Locations')

@section('content')
@php
  // Demo data (wire to DB later)
  $categories = ['IT & Software','Healthcare','Logistics','Construction','Customer Service'];
  $skills = ['JavaScript','Laravel','MySQL','Customer Support','MS Excel'];
  $locations = [
    ['city'=>'Santa Rosa', 'barangays'=>['Balibago','Tagapo','Pulong Santa Cruz']],
    ['city'=>'Parañaque', 'barangays'=>['San Dionisio','BF Homes','Tambo']],
  ];

  // Demo “suggestions” (UI only)
  $locationSuggestions = [
    ['country'=>'UAE','city'=>'Dubai','area'=>'Al Nahda','count'=>5],
    ['country'=>'Saudi Arabia','city'=>'Riyadh','area'=>'Al Olaya','count'=>3],
    ['country'=>'Philippines','city'=>'Cebu City','area'=>'Lahug','count'=>2],
  ];
@endphp

<div class="space-y-6"
  x-data="taxonomyUI({
    categories: @js($categories),
    skills: @js($skills),
    locations: @js($locations),
    suggestions: @js($locationSuggestions),
  })"
  x-init="init()"
>

  {{-- Header --}}
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <div class="min-w-0">
        <div class="text-sm font-semibold text-slate-900">Manage searchable lists</div>
        <div class="mt-1 text-xs text-slate-500">
          Add, edit, and remove categories, skills, and locations.
        </div>
      </div>

      <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
        <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
          <span class="text-slate-400">⌕</span>
          <input
            x-model.trim="q"
            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none sm:w-72"
            placeholder="Search…"
          />
        </div>

        <button
          type="button"
          @click="quickAdd()"
          class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
        >
          Quick Add
        </button>
      </div>
    </div>

    {{-- Tabs --}}
    <div class="mt-4 flex flex-wrap gap-2">
      <button type="button" @click="tab='categories'"
        class="rounded-xl px-4 py-2 text-sm font-semibold ring-1"
        :class="tab==='categories' ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
        Categories
      </button>
      <button type="button" @click="tab='skills'"
        class="rounded-xl px-4 py-2 text-sm font-semibold ring-1"
        :class="tab==='skills' ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
        Skills
      </button>
      <button type="button" @click="tab='locations'"
        class="rounded-xl px-4 py-2 text-sm font-semibold ring-1"
        :class="tab==='locations' ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
        Locations
      </button>
      <button type="button" @click="tab='suggestions'"
        class="rounded-xl px-4 py-2 text-sm font-semibold ring-1"
        :class="tab==='suggestions' ? 'bg-emerald-600 text-white ring-emerald-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50'">
        Suggestions
      </button>
    </div>
  </div>

  {{-- GRID --}}
  <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">

    {{-- Categories --}}
    <div x-show="tab==='categories'" x-transition class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold text-slate-900">Categories</div>
        <div class="mt-1 text-xs text-slate-500">Used in job posts and filters</div>
      </div>

      <div class="p-5">
        <div class="flex flex-col gap-2 sm:flex-row">
          <input
            x-model.trim="newCategory"
            @keydown.enter.prevent="addCategory()"
            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
            placeholder="Add a category"
          />
          <button type="button" @click="addCategory()"
            class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 disabled:opacity-50"
            :disabled="!newCategory">
            Add
          </button>
        </div>

        <template x-if="catFiltered.length === 0">
          <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
            No results.
          </div>
        </template>

        <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2 xl:grid-cols-3">
          <template x-for="(c, idx) in catFiltered" :key="'c'+idx">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
              <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 flex-1">
                  <template x-if="editKey !== ('cat'+idx)">
                    <div class="text-sm font-semibold text-slate-900 truncate" x-text="c"></div>
                  </template>

                  <template x-if="editKey === ('cat'+idx)">
                    <input
                      x-model.trim="editValue"
                      @keydown.enter.prevent="saveEdit('category', idx)"
                      class="mt-0.5 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                    />
                  </template>

                  <div class="mt-1 text-xs text-slate-500">Visible in filters</div>
                </div>

                <div class="flex shrink-0 gap-2">
                  <template x-if="editKey !== ('cat'+idx)">
                    <button type="button" @click="startEdit('category', idx, c)"
                      class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold hover:bg-slate-50">
                      Edit
                    </button>
                  </template>

                  <template x-if="editKey === ('cat'+idx)">
                    <button type="button" @click="saveEdit('category', idx)"
                      class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                      Save
                    </button>
                  </template>

                  <button type="button" @click="removeCategory(idx)"
                    class="rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </template>
        </div>

        <div class="mt-4 text-xs text-slate-500">
          Keep names consistent and avoid duplicates.
        </div>
      </div>
    </div>

    {{-- Skills --}}
    <div x-show="tab==='skills'" x-transition class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold text-slate-900">Skills</div>
        <div class="mt-1 text-xs text-slate-500">Used in job requirements</div>
      </div>

      <div class="p-5">
        <div class="flex flex-col gap-2 sm:flex-row">
          <input
            x-model.trim="newSkill"
            @keydown.enter.prevent="addSkill()"
            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
            placeholder="Add a skill"
          />
          <button type="button" @click="addSkill()"
            class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 disabled:opacity-50"
            :disabled="!newSkill">
            Add
          </button>
        </div>

        <template x-if="skillFiltered.length === 0">
          <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
            No results.
          </div>
        </template>

        <div class="mt-4 flex flex-wrap gap-2">
          <template x-for="(s, idx) in skillFiltered" :key="'s'+idx">
            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-semibold text-slate-700">
              <template x-if="editKey !== ('skill'+idx)">
                <span x-text="s"></span>
              </template>

              <template x-if="editKey === ('skill'+idx)">
                <input
                  x-model.trim="editValue"
                  @keydown.enter.prevent="saveEdit('skill', idx)"
                  class="w-40 rounded-lg border border-slate-200 bg-white px-2 py-1 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                />
              </template>

              <template x-if="editKey !== ('skill'+idx)">
                <button type="button" class="text-slate-400 hover:text-slate-700" @click="startEdit('skill', idx, s)" title="Edit">✎</button>
              </template>

              <template x-if="editKey === ('skill'+idx)">
                <button type="button" class="text-emerald-600 hover:text-emerald-800" @click="saveEdit('skill', idx)" title="Save">✓</button>
              </template>

              <button type="button" class="text-rose-500 hover:text-rose-700" @click="removeSkill(idx)" title="Delete">×</button>
            </span>
          </template>
        </div>

        <div class="mt-4 text-xs text-slate-500">
          Use standard naming (example: “MS Excel”, not “excel”).
        </div>
      </div>
    </div>

    {{-- Locations --}}
    <div x-show="tab==='locations'" x-transition class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold text-slate-900">Locations</div>
        <div class="mt-1 text-xs text-slate-500">Cities and barangays for filters</div>
      </div>

      <div class="p-5 space-y-4">

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="text-xs font-semibold text-slate-700">Add City</div>
          <div class="mt-2 flex flex-col gap-2 sm:flex-row">
            <input
              x-model.trim="newCity"
              @keydown.enter.prevent="addCity()"
              class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
              placeholder="City name"
            />
            <button type="button" @click="addCity()"
              class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 disabled:opacity-50"
              :disabled="!newCity">
              Add
            </button>
          </div>
        </div>

        <template x-if="locFiltered.length === 0">
          <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
            No results.
          </div>
        </template>

        <div class="space-y-3">
          <template x-for="(loc, idx) in locFiltered" :key="'loc'+idx">
            <div class="rounded-2xl border border-slate-200 p-4">
              <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                  <div class="flex items-center gap-2">
                    <button type="button" class="grid h-8 w-8 place-items-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50"
                      @click="toggleCity(idx)"
                      :title="openCities[idx] ? 'Collapse' : 'Expand'">
                      <span x-text="openCities[idx] ? '−' : '+'"></span>
                    </button>

                    <template x-if="editKey !== ('city'+idx)">
                      <div class="text-sm font-semibold text-slate-900 truncate" x-text="loc.city"></div>
                    </template>

                    <template x-if="editKey === ('city'+idx)">
                      <input
                        x-model.trim="editValue"
                        @keydown.enter.prevent="saveEdit('city', idx)"
                        class="w-56 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                      />
                    </template>
                  </div>
                  <div class="mt-1 text-xs text-slate-500">
                    <span x-text="(loc.barangays?.length || 0) + ' barangays'"></span>
                  </div>
                </div>

                <div class="flex gap-2">
                  <template x-if="editKey !== ('city'+idx)">
                    <button type="button" @click="startEdit('city', idx, loc.city)"
                      class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold hover:bg-slate-50">
                      Edit
                    </button>
                  </template>

                  <template x-if="editKey === ('city'+idx)">
                    <button type="button" @click="saveEdit('city', idx)"
                      class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                      Save
                    </button>
                  </template>

                  <button type="button" @click="removeCity(idx)"
                    class="rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">
                    Delete
                  </button>
                </div>
              </div>

              <div x-show="openCities[idx]" x-transition class="mt-4 space-y-3">
                <div class="flex flex-wrap gap-2">
                  <template x-for="(b, bidx) in loc.barangays" :key="'b'+idx+'_'+bidx">
                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                      <span x-text="b"></span>
                      <button type="button" class="text-rose-500 hover:text-rose-700" @click="removeBarangay(idx, bidx)" title="Remove">×</button>
                    </span>
                  </template>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row">
                  <input
                    x-model.trim="barangayDraft[idx]"
                    @keydown.enter.prevent="addBarangay(idx)"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100"
                    :placeholder="'Add barangay to ' + loc.city"
                  />
                  <button type="button" @click="addBarangay(idx)"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50 disabled:opacity-50"
                    :disabled="!(barangayDraft[idx] && barangayDraft[idx].length)">
                    Add
                  </button>
                </div>

                <div class="text-xs text-slate-500">
                  Keep spelling consistent to avoid duplicates.
                </div>
              </div>
            </div>
          </template>
        </div>

      </div>
    </div>

    {{-- Suggestions --}}
    <div x-show="tab==='suggestions'" x-transition class="xl:col-span-3 rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-5">
        <div class="text-sm font-semibold text-slate-900">Suggestions</div>
        <div class="mt-1 text-xs text-slate-500">
          New locations suggested by users. Approve to add them to the list.
        </div>
      </div>

      <div class="p-5">
        <template x-if="suggestFiltered.length === 0">
          <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
            No results.
          </div>
        </template>

        <div class="space-y-2">
          <template x-for="(s, idx) in suggestFiltered" :key="'sg'+idx">
            <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between">
              <div class="min-w-0">
                <div class="text-sm font-semibold text-slate-900">
                  <span x-text="s.country"></span> • <span x-text="s.city"></span> • <span x-text="s.area"></span>
                </div>
                <div class="mt-1 text-xs text-slate-500">
                  Requested <span class="font-semibold" x-text="s.count"></span> time(s)
                </div>
              </div>

              <div class="flex gap-2">
                <button type="button" @click="approveSuggestion(idx)"
                  class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                  Approve
                </button>
                <button type="button" @click="ignoreSuggestion(idx)"
                  class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold hover:bg-slate-50">
                  Ignore
                </button>
              </div>
            </div>
          </template>
        </div>

        <div class="mt-4 text-xs text-slate-500">
          Backend later: approvals should write to the taxonomy tables.
        </div>
      </div>
    </div>

  </div>

</div>

<script>
  function taxonomyUI(seed){
    return {
      tab: 'categories',
      q: '',

      categories: [],
      skills: [],
      locations: [],
      suggestions: [],

      newCategory: '',
      newSkill: '',
      newCity: '',
      barangayDraft: {},

      editKey: null,
      editValue: '',

      openCities: {},

      // ✅ UPDATED: use layout toast (window.notify) only
      toast(type, msg, title = ''){
        if (!window.notify) return;
        const allowed = ['success','info','warning','error'];
        const safeType = allowed.includes(type) ? type : 'info';
        window.notify(safeType, String(msg || ''), String(title || ''));
      },

      init(){
        this.categories = [...(seed.categories || [])];
        this.skills = [...(seed.skills || [])];
        this.locations = (seed.locations || []).map(x => ({
          city: x.city,
          barangays: [...(x.barangays || [])],
        }));
        this.suggestions = [...(seed.suggestions || [])];

        if(this.locations.length) this.openCities[0] = true;

        this.toast('info', 'Taxonomy ready');
      },

      get catFiltered(){
        const q = this.q.toLowerCase();
        return this.categories
          .filter(x => x.toLowerCase().includes(q))
          .sort((a,b)=>a.localeCompare(b));
      },
      get skillFiltered(){
        const q = this.q.toLowerCase();
        return this.skills
          .filter(x => x.toLowerCase().includes(q))
          .sort((a,b)=>a.localeCompare(b));
      },
      get locFiltered(){
        const q = this.q.toLowerCase();
        return this.locations.filter(loc => {
          if(!q) return true;
          const cityHit = (loc.city || '').toLowerCase().includes(q);
          const brgyHit = (loc.barangays || []).some(b => (b||'').toLowerCase().includes(q));
          return cityHit || brgyHit;
        });
      },
      get suggestFiltered(){
        const q = this.q.toLowerCase();
        return this.suggestions.filter(s => {
          if(!q) return true;
          return [s.country, s.city, s.area].some(v => String(v||'').toLowerCase().includes(q));
        });
      },

      norm(s){
        return String(s || '').trim().replace(/\s+/g,' ');
      },
      existsIn(list, value){
        const v = this.norm(value).toLowerCase();
        return list.some(x => this.norm(x).toLowerCase() === v);
      },

      quickAdd(){
        if(this.tab === 'categories'){
          const v = prompt('Add category:');
          if(v) { this.newCategory = v; this.addCategory(); }
        } else if(this.tab === 'skills'){
          const v = prompt('Add skill:');
          if(v) { this.newSkill = v; this.addSkill(); }
        } else if(this.tab === 'locations'){
          const v = prompt('Add city:');
          if(v) { this.newCity = v; this.addCity(); }
        } else {
          this.toast('info', 'Suggestions are user-generated in this demo.');
        }
      },

      addCategory(){
        const v = this.norm(this.newCategory);
        if(!v) { this.toast('warning', 'Please enter a category.'); return; }
        if(this.existsIn(this.categories, v)) { this.toast('warning', 'Category already exists.'); return; }
        this.categories.push(v);
        this.newCategory = '';
        this.toast('success', 'Category added (demo).');
      },
      removeCategory(idx){
        if(!confirm('Delete this category?')) return;
        const removed = this.categories[idx];
        this.categories.splice(idx, 1);
        if(this.editKey === ('cat'+idx)) this.cancelEdit();
        this.toast('success', `Deleted category: ${removed || ''}`);
      },

      addSkill(){
        const v = this.norm(this.newSkill);
        if(!v) { this.toast('warning', 'Please enter a skill.'); return; }
        if(this.existsIn(this.skills, v)) { this.toast('warning', 'Skill already exists.'); return; }
        this.skills.push(v);
        this.newSkill = '';
        this.toast('success', 'Skill added (demo).');
      },
      removeSkill(idx){
        if(!confirm('Delete this skill?')) return;
        const removed = this.skills[idx];
        this.skills.splice(idx, 1);
        if(this.editKey === ('skill'+idx)) this.cancelEdit();
        this.toast('success', `Deleted skill: ${removed || ''}`);
      },

      addCity(){
        const v = this.norm(this.newCity);
        if(!v) { this.toast('warning', 'Please enter a city.'); return; }
        if(this.locations.some(l => this.norm(l.city).toLowerCase() === v.toLowerCase())){
          this.toast('warning', 'City already exists.');
          return;
        }
        this.locations.push({ city: v, barangays: [] });
        const newIndex = this.locations.length - 1;
        this.openCities[newIndex] = true;
        this.newCity = '';
        this.toast('success', 'City added (demo).');
      },
      removeCity(idx){
        if(!confirm('Delete this city and its barangays?')) return;
        const removed = this.locations[idx]?.city || '';
        this.locations.splice(idx, 1);
        delete this.openCities[idx];
        delete this.barangayDraft[idx];
        this.cancelEdit();
        this.toast('success', `Deleted city: ${removed}`);
      },
      toggleCity(idx){
        this.openCities[idx] = !this.openCities[idx];
      },
      addBarangay(cityIdx){
        const v = this.norm(this.barangayDraft[cityIdx]);
        if(!v) { this.toast('warning', 'Please enter a barangay.'); return; }
        const brgys = this.locations[cityIdx].barangays || [];
        if(brgys.some(b => this.norm(b).toLowerCase() === v.toLowerCase())){
          this.toast('warning', 'Barangay already exists in this city.');
          return;
        }
        brgys.push(v);
        this.locations[cityIdx].barangays = brgys;
        this.barangayDraft[cityIdx] = '';
        this.toast('success', 'Barangay added (demo).');
      },
      removeBarangay(cityIdx, bIdx){
        if(!confirm('Remove this barangay?')) return;
        const removed = this.locations[cityIdx]?.barangays?.[bIdx] || '';
        this.locations[cityIdx].barangays.splice(bIdx, 1);
        this.toast('success', `Removed barangay: ${removed}`);
      },

      approveSuggestion(idx){
        const s = this.suggestions[idx];
        if(!s) return;

        const cityName = this.norm(s.city);
        let cityIdx = this.locations.findIndex(l => this.norm(l.city).toLowerCase() === cityName.toLowerCase());
        if(cityIdx === -1){
          this.locations.push({ city: cityName, barangays: [] });
          cityIdx = this.locations.length - 1;
        }

        const area = this.norm(s.area);
        if(area){
          const brgys = this.locations[cityIdx].barangays || [];
          const exists = brgys.some(b => this.norm(b).toLowerCase() === area.toLowerCase());
          if(!exists) brgys.push(area);
          this.locations[cityIdx].barangays = brgys;
          this.openCities[cityIdx] = true;
        }

        this.suggestions.splice(idx, 1);
        this.toast('success', 'Approved (demo). Added to Locations list.');
      },
      ignoreSuggestion(idx){
        if(!confirm('Ignore this suggestion?')) return;
        this.suggestions.splice(idx, 1);
        this.toast('info', 'Suggestion ignored (demo).');
      },

      startEdit(type, idx, current){
        this.editValue = this.norm(current);
        if(type === 'category') this.editKey = 'cat'+idx;
        if(type === 'skill') this.editKey = 'skill'+idx;
        if(type === 'city') this.editKey = 'city'+idx;
      },
      cancelEdit(){
        this.editKey = null;
        this.editValue = '';
      },
      saveEdit(type, idx){
        const v = this.norm(this.editValue);
        if(!v) { this.toast('error', 'Value cannot be empty.'); return; }

        if(type === 'category'){
          const other = this.categories.filter((_,i)=>i!==idx);
          if(this.existsIn(other, v)) { this.toast('warning', 'Duplicate category.'); return; }
          this.categories[idx] = v;
          this.toast('success', 'Category updated (demo).');
        }

        if(type === 'skill'){
          const other = this.skills.filter((_,i)=>i!==idx);
          if(this.existsIn(other, v)) { this.toast('warning', 'Duplicate skill.'); return; }
          this.skills[idx] = v;
          this.toast('success', 'Skill updated (demo).');
        }

        if(type === 'city'){
          const other = this.locations.filter((_,i)=>i!==idx).map(x=>x.city);
          if(this.existsIn(other, v)) { this.toast('warning', 'Duplicate city.'); return; }
          this.locations[idx].city = v;
          this.toast('success', 'City updated (demo).');
        }

        this.cancelEdit();
      },
    }
  }
</script>

@endsection
