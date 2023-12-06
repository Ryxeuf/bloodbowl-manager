import {EditGuesser, InputGuesser} from "@api-platform/admin";
import {TextInput} from "react-admin";

export const SkillEdit = props => (
    <EditGuesser {...props}>
        <InputGuesser source={"name"} fullWidth={true} />
        <InputGuesser source={"type"} fullWidth={true} />
        <InputGuesser source={"category"} fullWidth={true} />
        <TextInput source={"description"} multiline={true} fullWidth={true} />
    </EditGuesser>
);