import { useForm } from '@inertiajs/react';

export default function CreateStudent() {
    const { data, setData, post, errors } = useForm({
        full_name: '',
        email: '',
        rfid_number: '',
    });

    function handleSubmit(e) {
        e.preventDefault();
        post(route('students.store'));
    }

    return (
        <div>
            <h1>Add Student</h1>
            <form onSubmit={handleSubmit}>
                <div>
                    <label>Full Name</label>
                    <input
                        type="text"
                        value={data.full_name}
                        onChange={(e) => setData('full_name', e.target.value)}
                    />
                    {errors.full_name && <div>{errors.full_name}</div>}
                </div>
                <div>
                    <label>Email</label>
                    <input
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                    />
                    {errors.email && <div>{errors.email}</div>}
                </div>
                <div>
                    <label>RFID Number</label>
                    <input
                        type="text"
                        value={data.rfid_number}
                        onChange={(e) => setData('rfid_number', e.target.value)}
                    />
                    {errors.rfid_number && <div>{errors.rfid_number}</div>}
                </div>
                <button type="submit">Add Student</button>
            </form>
        </div>
    );
}
