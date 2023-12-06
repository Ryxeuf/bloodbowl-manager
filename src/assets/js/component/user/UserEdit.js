import {EditGuesser, InputGuesser} from "@api-platform/admin";

export const UserEdit = props => (
    <EditGuesser {...props}>
        <InputGuesser source={"username"} />
        <InputGuesser source={"roles"} />
        <InputGuesser source={"email"} />
    </EditGuesser>
);