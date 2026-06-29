import "@/styles/aurora.scss";

export default function Aurora() {
  return (
    <div className="aurora" aria-hidden="true">
      <div className="aurora__grid" />
      <div className="aurora__blob aurora__blob--one" />
      <div className="aurora__blob aurora__blob--two" />
      <div className="aurora__blob aurora__blob--three" />
    </div>
  );
}
